<?php

namespace App\Http\Controllers;

use App\Models\TestCategory;
use Illuminate\Http\Request;

class TestCategoryController extends Controller
{
     public function index()
    {
        $categories = TestCategory::latest()->paginate(10);
        return view('test_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('test_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', 'unique:test_categories,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        TestCategory::create([
            'name' => $data['name'],
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        return redirect()->route('test-categories.index')->with('success', 'Test Category created successfully.');
    }

    public function edit(TestCategory $testCategory)
    {
        return view('test_categories.edit', compact('testCategory'));
    }

    public function update(Request $request, TestCategory $testCategory)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', 'unique:test_categories,name,' . $testCategory->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $testCategory->update([
            'name' => $data['name'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return redirect()->route('test-categories.index')->with('success', 'Test Category updated successfully.');
    }

    public function destroy(TestCategory $testCategory)
    {
        $testCategory->delete();
        return redirect()->route('test-categories.index')->with('success', 'Test Category deleted successfully.');
    }

    public function show(TestCategory $testCategory) { abort(404); }
}
