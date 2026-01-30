<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');

        // Only admin can manage branches (based on users.category)
        $this->middleware(function ($request, $next) {
            if (auth()->user()->category !== 'admin') {
                abort(403, 'Only admin can manage branches.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $branches = Branch::with('user')->latest()->paginate(10);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Branch profile
            'branch_name' => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'address'     => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],

            // Branch login (same users table)
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'category' => 'branch',
            ]);

            Branch::create([
                'user_id'     => $user->id,
                'branch_name' => $data['branch_name'],
                'phone'       => $data['phone'] ?? null,
                'address'     => $data['address'] ?? null,
                'is_active'   => (bool)($data['is_active'] ?? true),
            ]);
        });

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $branch->load('user');
        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $branch->load('user');
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $branch->load('user');

        $data = $request->validate([
            // Branch profile
            'branch_name' => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'address'     => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],

            // Branch login
            'name'        => ['required', 'string', 'max:255'],
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($branch->user_id),
            ],
            'password'    => ['nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($data, $branch) {
            $branch->update([
                'branch_name' => $data['branch_name'],
                'phone'       => $data['phone'] ?? null,
                'address'     => $data['address'] ?? null,
                'is_active'   => (bool)($data['is_active'] ?? false),
            ]);

            $branch->user->update([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'category' => 'branch', // force it to stay branch
            ]);

            if (!empty($data['password'])) {
                $branch->user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }
        });

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        DB::transaction(function () use ($branch) {
            // Delete the branch login user; branch will auto-delete (FK cascade)
            User::where('id', $branch->user_id)->delete();
        });

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
