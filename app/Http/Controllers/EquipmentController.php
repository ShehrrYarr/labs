<?php

namespace App\Http\Controllers;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $u = auth()->user();
            if ($u->category === 'admin') return $next($request);
            if ($u->category === 'staff' && $u->hasStaffPermission('manage_equipment')) return $next($request);
            abort(403, 'Unauthorized.');
        });
    }

    public function index()
    {
        $equipment = Equipment::latest()->paginate(10);
        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', 'unique:equipment,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Equipment::create([
            'name' => $data['name'],
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        return redirect()->route('equipment.index')->with('success', 'Equipment created successfully.');
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255', 'unique:equipment,name,' . $equipment->id],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $equipment->update([
            'name' => $data['name'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }

    public function show(Equipment $equipment) { abort(404); }
}
