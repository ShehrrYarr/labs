<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->category !== 'admin') {
                abort(403, 'Only admin can manage staff.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $staffList = User::where('category', 'staff')->latest()->get();
        return view('staff.index', compact('staffList'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'password_text'     => $request->password,
            'category'          => 'staff',
            'is_active'         => true,
            'staff_permissions' => [],
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    public function edit(User $staff)
    {
        if ($staff->category !== 'staff') {
            abort(404);
        }

        $allPermissions = $this->allPermissions();
        return view('staff.edit', compact('staff', 'allPermissions'));
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->category !== 'staff') {
            abort(404);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password']      = Hash::make($request->password);
            $data['password_text'] = $request->password;
        }

        $staff->update($data);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->category !== 'staff') {
            abort(404);
        }

        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed.');
    }

    public function updatePermissions(Request $request, User $staff)
    {
        if ($staff->category !== 'staff') {
            abort(404);
        }

        $allowed = array_keys($this->allPermissions());
        $permissions = array_values(array_intersect(
            $request->input('permissions', []),
            $allowed
        ));

        $staff->update(['staff_permissions' => $permissions]);

        return redirect()->route('staff.edit', $staff)->with('success', 'Permissions updated.');
    }

    private function allPermissions(): array
    {
        return [
            'create_customers' => 'Create Customers',
            'create_orders'    => 'Create & Manage Orders',
            'assign_tests'     => 'Assign Tests to Orders',
            'enter_results'    => 'Enter Test Results',
            'manage_payments'  => 'Manage Payments & Discounts',
            'print_reports'    => 'Print Reports / Slips / Receipts',
            'price_calculator' => 'Price Calculator',
            'delete_tests'     => 'Remove Assigned Tests from Orders',
            'delete_orders'    => 'Delete Entire Orders',
        ];
    }
}
