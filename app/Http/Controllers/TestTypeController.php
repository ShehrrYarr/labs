<?php

namespace App\Http\Controllers;
use App\Models\TestType;
use Illuminate\Http\Request;

class TestTypeController extends Controller
{
     public function index()
    {
        $testTypes = TestType::latest()->paginate(10);
        return view('test_types.index', compact('testTypes'));
    }

    public function create()
    {
        return view('test_types.create');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name'        => ['required', 'string', 'max:255', 'unique:test_types,name'],
        'code'        => ['nullable', 'string', 'max:50', 'unique:test_types,code'],
        'price'       => ['required', 'numeric', 'min:0'],
        'description' => ['nullable', 'string', 'max:2000'],
        'is_active'   => ['nullable', 'boolean'],
    ]);

    TestType::create([
        'name'        => $data['name'],
        'code'        => $data['code'] ?? null,
        'price'       => (float) $data['price'],
        'description' => $data['description'] ?? null,
        'is_active'   => (bool)($data['is_active'] ?? true),
    ]);

    return redirect()
        ->route('test-types.index')
        ->with('success', 'Test Type created successfully.');
}

    public function edit(TestType $testType)
    {
        return view('test_types.edit', compact('testType'));
    }

    public function update(Request $request, TestType $testType)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', 'unique:test_types,name,' . $testType->id],
            'code'      => ['nullable', 'string', 'max:50', 'unique:test_types,code,' . $testType->id],
            'price'     => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $testType->update([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'price' => (float) $data['price'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return redirect()->route('test-types.index')->with('success', 'Test Type updated successfully.');
    }

    public function destroy(TestType $testType)
    {
        $testType->delete();
        return redirect()->route('test-types.index')->with('success', 'Test Type deleted successfully.');
    }

    // Not needed for now
    public function show(TestType $testType) { abort(404); }
}
