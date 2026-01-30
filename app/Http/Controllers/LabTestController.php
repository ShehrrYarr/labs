<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\LabTest;
use App\Models\TestCategory;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LabTestController extends Controller
{
      public function index()
    {
        $tests = LabTest::with(['testType', 'testCategory'])
            ->latest()
            ->paginate(10);

        return view('lab_tests.index', compact('tests'));
    }

   public function create()
{
    // ✅ include price because UI will show it
    $types = TestType::where('is_active', true)
        ->orderBy('name')
        ->get(['id','name','price']);

    $categories = TestCategory::where('is_active', true)->orderBy('name')->get();
    $equipment  = Equipment::where('is_active', true)->orderBy('name')->get();

    return view('lab_tests.create', compact('types', 'categories', 'equipment'));
}

   public function store(Request $request)
{
    $data = $request->validate([
        'test_case_image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        'test_name'            => ['required', 'string', 'max:255'],
        'test_code'            => ['required', 'string', 'max:100', 'unique:lab_tests,test_code'],

        'test_type_id'         => ['required', 'integer', 'exists:test_types,id'],
        'test_category_id'     => ['required', 'integer', 'exists:test_categories,id'],

        'equipment_ids'        => ['nullable', 'array'],
        'equipment_ids.*'      => ['integer', 'exists:equipment,id'],

        'description'          => ['nullable', 'string', 'max:5000'],

        // ✅ price removed from LabTest
        'reporting_time'  => ['required', 'string', 'max:100'],
        'test_instruction'     => ['nullable', 'string', 'max:5000'],
        'additional_notes'     => ['nullable', 'string', 'max:5000'],

        'unit'                 => ['nullable', 'string', 'max:255'],
        'reference_range'      => ['nullable', 'string', 'max:1000'],

        'is_active'            => ['nullable', 'boolean'],
    ]);

    DB::transaction(function () use ($request, $data) {
        $imagePath = null;

        if ($request->hasFile('test_case_image')) {
            $imagePath = $request->file('test_case_image')->store('test_case_images', 'public');
        }

        $test = LabTest::create([
            'test_case_image'     => $imagePath,
            'test_name'           => $data['test_name'],
            'test_code'           => $data['test_code'],
            'test_type_id'        => $data['test_type_id'],
            'test_category_id'    => $data['test_category_id'],
            'description'         => $data['description'] ?? null,

            // ✅ price removed
            'reporting_time' => $data['reporting_time'],
            'test_instruction'    => $data['test_instruction'] ?? null,
            'additional_notes'    => $data['additional_notes'] ?? null,

            'unit'                => $data['unit'] ?? null,
            'reference_range'     => $data['reference_range'] ?? null,

            'is_active'           => (bool)($data['is_active'] ?? true),
        ]);

        $test->equipment()->sync($data['equipment_ids'] ?? []);
    });

    return redirect()->route('lab-tests.index')->with('success', 'Test created successfully.');
}

   public function edit(LabTest $labTest)
{
    $types = TestType::where('is_active', true)->orderBy('name')->get();
    $categories = TestCategory::where('is_active', true)->orderBy('name')->get();
    $equipment = Equipment::where('is_active', true)->orderBy('name')->get();

    $labTest->load(['equipment', 'testType']); // ✅ load type to show price

    return view('lab_tests.edit', compact('labTest', 'types', 'categories', 'equipment'));
}

public function update(Request $request, LabTest $labTest)
{
    $data = $request->validate([
        'test_case_image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        'test_name'            => ['required', 'string', 'max:255'],
        'test_code'            => [
            'required', 'string', 'max:100',
            Rule::unique('lab_tests', 'test_code')->ignore($labTest->id),
        ],

        'test_type_id'         => ['required', 'integer', 'exists:test_types,id'],
        'test_category_id'     => ['required', 'integer', 'exists:test_categories,id'],

        'equipment_ids'        => ['nullable', 'array'],
        'equipment_ids.*'      => ['integer', 'exists:equipment,id'],

        'description'          => ['nullable', 'string', 'max:5000'],
        'reporting_time'  => ['required', 'string', 'max:100'],
        'test_instruction'     => ['nullable', 'string', 'max:5000'],
        'additional_notes'     => ['nullable', 'string', 'max:5000'],
        'unit'                 => ['nullable', 'string', 'max:255'],
        'reference_range'      => ['nullable', 'string', 'max:5000'],

        'is_active'            => ['nullable', 'boolean'],
    ]);

    DB::transaction(function () use ($request, $data, $labTest) {

        $imagePath = $labTest->test_case_image;

        if ($request->hasFile('test_case_image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('test_case_image')->store('test_case_images', 'public');
        }

        $labTest->update([
            'test_case_image'     => $imagePath,
            'test_name'           => $data['test_name'],
            'test_code'           => $data['test_code'],
            'test_type_id'        => $data['test_type_id'],
            'test_category_id'    => $data['test_category_id'],
            'description'         => $data['description'] ?? null,
            'unit'                => $data['unit'] ?? null,
            'reference_range'     => $data['reference_range'] ?? null,
            'reporting_time' => $data['reporting_time'],
            'test_instruction'    => $data['test_instruction'] ?? null,
            'additional_notes'    => $data['additional_notes'] ?? null,
            'is_active'           => (bool)($data['is_active'] ?? false),
        ]);

        // ✅ only if relationship exists
        $labTest->equipment()->sync($data['equipment_ids'] ?? []);
    });

    return redirect()->route('lab-tests.index')->with('success', 'Test updated successfully.');
}

    public function destroy(LabTest $labTest)
    {
        DB::transaction(function () use ($labTest) {
            if ($labTest->test_case_image) {
                Storage::disk('public')->delete($labTest->test_case_image);
            }
            $labTest->equipment()->detach();
            $labTest->delete();
        });

        return redirect()->route('lab-tests.index')->with('success', 'Test deleted successfully.');
    }

    // Not needed now
    public function show(LabTest $labTest) { abort(404); }
}
