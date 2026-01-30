<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{

public function __construct()
    {
        $this->middleware('auth');

        // Only admin OR branch users can manage customers
        $this->middleware(function ($request, $next) {
            $cat = auth()->user()->category ?? null;
            if (!in_array($cat, ['admin', 'branch'], true)) {
                abort(403, 'Only admin or branch can manage customers.');
            }
            return $next($request);
        });
    }


     public function index()
    {
        $user = auth()->user();

        $query = Customer::with(['user', 'createdByBranch']);

        // Branch should only see customers created by their branch
        if ($user->category === 'branch') {
            $branchId = optional($user->branch)->id;
            $query->where('created_by_branch_id', $branchId);
        }

        $customers = $query->latest()->paginate(10);




         if (auth()->user()->category === 'branch') {
            return view('branches.customers.index', compact('customers'));
}else 
        return view('customers.index', compact('customers'));
    }

    public function create()
    {


     if (auth()->user()->category === 'branch') {
           return view('branches.customers.create');
}else 
        return view('customers.create');



        
    }

   public function store(Request $request)
{
    $data = $request->validate([
        'name'      => ['required', 'string', 'max:255'],
        'phone'     => ['nullable', 'string', 'max:30'],
        'address'   => ['nullable', 'string', 'max:1000'],
        'dob'       => ['nullable', 'date'],
        'gender'    => ['nullable', Rule::in(['male', 'female', 'other'])],
        'is_active' => ['nullable', 'boolean'],
        'ref_by'    => ['nullable', 'string', 'max:255'],
    ]);

    $authUser = auth()->user();

    // 8 digits login id (e.g. 10469413)
    $makeLoginDigits = function (): string {
        return str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    };

    // 6 chars password (letters + digits)
    $makePassword = function (): string {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        $out = '';
        for ($i = 0; $i < 6; $i++) {
            $out .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $out;
    };

    $createdLoginId = null;
    $plainPassword  = null;

    DB::transaction(function () use ($data, $authUser, $makeLoginDigits, $makePassword, &$createdLoginId, &$plainPassword) {

        // unique login_id (8 digits)
        do {
            $loginId = $makeLoginDigits();
        } while (User::where('login_id', $loginId)->exists());

        $plain = $makePassword();

        // customer email = 8digits@alghanilab.com
        $email = $loginId . '@alghanilab.com';
        $i = 1;
        while (User::where('email', $email)->exists()) {
            $email = $loginId . "+{$i}@alghanilab.com";
            $i++;
        }

        $user = User::create([
            'name'          => $data['name'],
            'email'         => $email,
            'login_id'      => $loginId,
            'password'      => Hash::make($plain),
            'password_text' => $plain,
            'category'      => 'customer',
        ]);

        Customer::create([
            'user_id' => $user->id,

            'created_by_user_id'   => $authUser->id,
            'created_by_branch_id' => $authUser->category === 'branch'
                ? optional($authUser->branch)->id
                : null,

            'phone'     => $data['phone'] ?? null,
            'address'   => $data['address'] ?? null,
            'dob'       => $data['dob'] ?? null,
            'gender'    => $data['gender'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? true),
            'ref_by'    => $data['ref_by'] ?? null,
        ]);

        $createdLoginId = $loginId;
        $plainPassword  = $plain;
    });

    return redirect()
        ->route('customers.index')
        ->with('success', 'Customer created successfully.')
        ->with('created_login_id', $createdLoginId)
        ->with('created_password', $plainPassword);
}



    public function edit(Customer $customer)
    {
        $this->authorizeCustomerAccess($customer);
        $customer->load('user');

        if (auth()->user()->category === 'branch') {
            return view('branches.customers.edit', compact('customer'));
}else 
        return view('customers.edit', compact('customer'));

        
    }

    public function update(Request $request, Customer $customer)
{
    $this->authorizeCustomerAccess($customer);
    $customer->load('user');

    $data = $request->validate([
        // login fields
        'name'     => ['required', 'string', 'max:255'],
        // 'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($customer->user_id)],
        'password' => ['nullable', 'string', 'min:6'],

        // profile fields
        'phone'     => ['nullable', 'string', 'max:30'],
        'address'   => ['nullable', 'string', 'max:1000'],
        'dob'       => ['nullable', 'date'],
        'gender'    => ['nullable', Rule::in(['male', 'female', 'other'])],
        'is_active' => ['nullable', 'boolean'],
        'ref_by'    => ['nullable', 'string', 'max:255'],
    ]);

    DB::transaction(function () use ($data, $customer) {

        // Update user basic info
        $customer->user->update([
            'name'     => $data['name'],
            'category' => 'customer',
        ]);

        // If password provided, update both hashed and plain text
        if (!empty($data['password'])) {
            $customer->user->update([
                'password'       => Hash::make($data['password']),
                'password_text'  => $data['password'],
            ]);
        }

        // Update customer profile
        $customer->update([
            'phone'     => $data['phone'] ?? null,
            'address'   => $data['address'] ?? null,
            'dob'       => $data['dob'] ?? null,
            'gender'    => $data['gender'] ?? null,
            'is_active' => (bool)($data['is_active'] ?? false),
            'ref_by'    => $data['ref_by'] ?? null,
        ]);
    });

    return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
}


    public function destroy(Customer $customer)
    {
        $this->authorizeCustomerAccess($customer);

        DB::transaction(function () use ($customer) {
            // deleting user cascades customer (FK cascade)
            User::where('id', $customer->user_id)->delete();
        });

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function show(Customer $customer) { abort(404); }

    private function authorizeCustomerAccess(Customer $customer): void
    {
        $user = auth()->user();

        if ($user->category === 'admin') {
            return;
        }

        // branch can only manage its own created customers
        $branchId = optional($user->branch)->id;

        if (!$branchId || $customer->created_by_branch_id !== $branchId) {
            abort(403, 'You do not have permission to access this customer.');
        }
    }

    public function search(Request $request)
{
    $user = auth()->user();
    if (!$user) abort(401);

    // Only admin/branch can use search
    if (!in_array($user->category, ['admin', 'branch'], true)) {
        abort(403);
    }

    $q = trim((string) $request->get('q', ''));

    if (mb_strlen($q) < 1) {
        return response()->json(['data' => []]);
    }

    $query = Customer::query()
        ->with([
            'user:id,name,email,login_id,password_text', // keep it light
            'createdByBranch:id,branch_name',
        ])
        ->where(function ($qq) use ($q) {

            // Search inside user table fields
            $qq->whereHas('user', function ($u) use ($q) {
                $u->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('login_id', 'like', "%{$q}%")
                  ->orWhere('password_text', 'like', "%{$q}%");
            })

            // Search inside customer profile fields
            ->orWhere('phone', 'like', "%{$q}%")
            ->orWhere('ref_by', 'like', "%{$q}%");
        });

    // Branch scope: only their created customers
    if ($user->category === 'branch') {
        $branchId = optional($user->branch)->id;
        $query->where('created_by_branch_id', $branchId);
    }

    $customers = $query
        ->orderByDesc('id')
        ->limit(50)
        ->get();

    $data = $customers->map(function ($c) use ($user) {
        return [
            'id' => $c->id,
            'is_active' => (bool) $c->is_active,
            'phone' => $c->phone ?? '-',
            'ref_by' => $c->ref_by ?? '-',

            // Keep same key used in your table for admin
            'branch_name' => ($user->category === 'admin')
                ? ($c->createdByBranch?->branch_name ?? 'Admin / Unknown')
                : null,

            // ✅ Return nested user object so JS can do c.user.login_id etc
            'user' => [
                'name' => $c->user?->name ?? 'Deleted',
                'login_id' => $c->user?->login_id ?? '-',
                'password_text' => $c->user?->password_text ?? '-',
                'email' => $c->user?->email ?? '-',
            ],
        ];
    });

    return response()->json(['data' => $data]);
}

}
