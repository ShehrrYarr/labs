<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\LabSubTest;
use App\Models\TestType;
use App\Models\TestCategory;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LabSubTestController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }

    private function authorizeAdminOrBranch(): void
    {
        $u = auth()->user();
        if (!$u) abort(401);

        if (!in_array($u->category, ['admin', 'branch'], true)) {
            abort(403);
        }
    }

    private function ensureBelongs(LabTest $labTest, LabSubTest $subTest): void
    {
        if ((int)$subTest->lab_test_id !== (int)$labTest->id) {
            abort(404);
        }
    }

    public function index(LabTest $labTest)
    {
        $this->authorizeAdminOrBranch();

        $subTests = LabSubTest::with(['testType', 'testCategory', 'requiredEquipment'])
            ->where('lab_test_id', $labTest->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('lab_sub_tests.index', compact('labTest', 'subTests'));
    }

    public function create(LabTest $labTest)
    {
        $this->authorizeAdminOrBranch();

        $types = TestType::where('is_active', true)->orderBy('name')->get();
        $categories = TestCategory::where('is_active', true)->orderBy('name')->get();

        // NOTE: use correct table/model (Equipment) as in your project
        $equipment = Equipment::where('is_active', true)->orderBy('name')->get();

        return view('lab_sub_tests.create', compact('labTest', 'types', 'categories', 'equipment'));
    }

    public function store(Request $request, LabTest $labTest)
    {
        $this->authorizeAdminOrBranch();

        $data = $request->validate([
            'test_case_image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'test_name'            => ['required', 'string', 'max:255'],
            'test_code'            => [
                'nullable', 'string', 'max:100',
                Rule::unique('lab_sub_tests', 'test_code')->where(fn($q) => $q->where('lab_test_id', $labTest->id)),
            ],

            'test_type_id'         => ['required', 'integer', 'exists:test_types,id'],
            'test_category_id'     => ['nullable', 'integer', 'exists:test_categories,id'],

            'required_equipment_id'=> ['nullable', 'integer', 'exists:equipment,id'], // change to equipments if that’s your table

            'description'          => ['nullable', 'string', 'max:5000'],
            'unit'                 => ['nullable', 'string', 'max:5000'],
            'reference_range'      => ['nullable', 'string', 'max:5000'],
            'reporting_time'       => ['nullable', 'string', 'max:100'],
            'test_instruction'     => ['nullable', 'string', 'max:5000'],
            'additional_notes'     => ['nullable', 'string', 'max:5000'],

            'sort_order'           => ['nullable', 'integer', 'min:0'],
            'is_active'            => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $data, $labTest) {
            $imagePath = null;

            if ($request->hasFile('test_case_image')) {
                $imagePath = $request->file('test_case_image')->store('test_case_images', 'public');
            }

            LabSubTest::create([
                'lab_test_id'           => $labTest->id,

                'test_type_id'          => $data['test_type_id'],
                'test_category_id'      => $data['test_category_id'] ?? null,
                'required_equipment_id' => $data['required_equipment_id'] ?? null,

                'test_name'             => $data['test_name'],
                'test_code'             => $data['test_code'] ?? null,
                'test_case_image'       => $imagePath,

                'description'           => $data['description'] ?? null,
                'unit'                  => $data['unit'] ?? null,
                'reference_range'       => $data['reference_range'] ?? null,

                'reporting_time'        => $data['reporting_time'] ?? null,
                'test_instruction'      => $data['test_instruction'] ?? null,
                'additional_notes'      => $data['additional_notes'] ?? null,

                'sort_order'            => (int)($data['sort_order'] ?? 0),
                'is_active'             => (bool)($data['is_active'] ?? true),
            ]);
        });

        return redirect()
            ->route('lab-tests.sub-tests.index', $labTest)
            ->with('success', 'Sub Test created successfully.');
    }

    public function edit(LabTest $labTest, LabSubTest $subTest)
    {
        $this->authorizeAdminOrBranch();
        $this->ensureBelongs($labTest, $subTest);

        $types = TestType::where('is_active', true)->orderBy('name')->get();
        $categories = TestCategory::where('is_active', true)->orderBy('name')->get();
        $equipment = Equipment::where('is_active', true)->orderBy('name')->get();

        $subTest->load(['testType', 'testCategory', 'requiredEquipment']);

        return view('lab_sub_tests.edit', compact('labTest', 'subTest', 'types', 'categories', 'equipment'));
    }

    public function update(Request $request, LabTest $labTest, LabSubTest $subTest)
    {
        $this->authorizeAdminOrBranch();
        $this->ensureBelongs($labTest, $subTest);

        $data = $request->validate([
            'test_case_image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'test_name'            => ['required', 'string', 'max:255'],
            'test_code'            => [
                'nullable', 'string', 'max:100',
                Rule::unique('lab_sub_tests', 'test_code')
                    ->where(fn($q) => $q->where('lab_test_id', $labTest->id))
                    ->ignore($subTest->id),
            ],

            'test_type_id'         => ['required', 'integer', 'exists:test_types,id'],
            'test_category_id'     => ['nullable', 'integer', 'exists:test_categories,id'],

            'required_equipment_id'=> ['nullable', 'integer', 'exists:equipment,id'], // change to equipments if that’s your table

            'description'          => ['nullable', 'string', 'max:5000'],
            'unit'                 => ['nullable', 'string', 'max:5000'],
            'reference_range'      => ['nullable', 'string', 'max:5000'],
            'reporting_time'       => ['nullable', 'string', 'max:100'],
            'test_instruction'     => ['nullable', 'string', 'max:5000'],
            'additional_notes'     => ['nullable', 'string', 'max:5000'],

            'sort_order'           => ['nullable', 'integer', 'min:0'],
            'is_active'            => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $data, $subTest) {
            $imagePath = $subTest->test_case_image;

            if ($request->hasFile('test_case_image')) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('test_case_image')->store('test_case_images', 'public');
            }

            $subTest->update([
                'test_type_id'          => $data['test_type_id'],
                'test_category_id'      => $data['test_category_id'] ?? null,
                'required_equipment_id' => $data['required_equipment_id'] ?? null,

                'test_name'             => $data['test_name'],
                'test_code'             => $data['test_code'] ?? null,
                'test_case_image'       => $imagePath,

                'description'           => $data['description'] ?? null,
                'unit'                  => $data['unit'] ?? null,
                'reference_range'       => $data['reference_range'] ?? null,

                'reporting_time'        => $data['reporting_time'] ?? null,
                'test_instruction'      => $data['test_instruction'] ?? null,
                'additional_notes'      => $data['additional_notes'] ?? null,

                'sort_order'            => (int)($data['sort_order'] ?? 0),
                'is_active'             => (bool)($data['is_active'] ?? false),
            ]);
        });

        return redirect()
            ->route('lab-tests.sub-tests.index', $labTest)
            ->with('success', 'Sub Test updated successfully.');
    }

    public function destroy(LabTest $labTest, LabSubTest $subTest)
    {
        $this->authorizeAdminOrBranch();
        $this->ensureBelongs($labTest, $subTest);

        DB::transaction(function () use ($subTest) {
            if ($subTest->test_case_image) {
                Storage::disk('public')->delete($subTest->test_case_image);
            }
            $subTest->delete();
        });

        return redirect()
            ->route('lab-tests.sub-tests.index', $labTest)
            ->with('success', 'Sub Test deleted successfully.');
    }
}
