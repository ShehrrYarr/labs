<?php

namespace App\Http\Controllers;

use App\Models\Mobile;
use App\Models\TransferRecord;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Models\TestType;
use App\Models\Equipment;
use App\Models\TestCategory;
use App\Models\LabTest;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\TestOrder;
use App\Models\TestOrderItem;
use App\Models\Invoice;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *90
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // ADMIN DASHBOARD STATS
    if ($user->category === 'admin') {

    $unfinishedOrders = \App\Models\TestOrder::with([
        'customer.user',
        'branch',
        'items'
    ])
    ->whereHas('items', function ($q) {
        $q->whereIn('result_status', ['pending', 'processing']);
    })
    ->latest()
    ->take(20)
    ->get();

        $testTypeCount = TestType::count();
        $equipmentCount = Equipment::count();
        $categoryCount  = TestCategory::count();
        $labTestsCount  = LabTest::count();

        $customerCount = Customer::count();
        $branchCount   = Branch::count();
        $ordersCount   = TestOrder::count();

        // totals from invoices (best source for amounts)
        $totalAmount = (float) Invoice::sum('total_amount');
        $paidAmount  = (float) Invoice::sum('paid_amount');
        $remainingAmount = max(0, $totalAmount - $paidAmount);

        return view('admin_dashboard', compact(
            'testTypeCount',
            'equipmentCount',
            'categoryCount',
            'labTestsCount',
            'customerCount',
            'branchCount',
            'ordersCount',
            'totalAmount',
            'paidAmount',
            'remainingAmount',
            'unfinishedOrders'
        ));
    }

    // BRANCH DASHBOARD STATS
    if ($user->category === 'branch') {

        $branchId = optional($user->branch)->id;

        // if branch record missing, show dashboard but with zeros
        if (!$branchId) {
            $branchCustomerCount = 0;
            $branchOrdersCount = 0;
            $branchTotalAmount = 0;
            $branchPaidAmount = 0;
            $branchRemainingAmount = 0;

            return view('branches.branch_dashboard', compact(
                'branchCustomerCount',
                'branchOrdersCount',
                'branchTotalAmount',
                'branchPaidAmount',
                'branchRemainingAmount'
            ));
        }

        $branchCustomerCount = Customer::where('created_by_branch_id', $branchId)->count();

        $branchOrdersCount = TestOrder::where('branch_id', $branchId)->count();

        // sum invoices for orders of this branch
        $branchTotalAmount = (float) Invoice::whereHas('order', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('total_amount');

        $branchPaidAmount = (float) Invoice::whereHas('order', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->sum('paid_amount');

        $branchRemainingAmount = max(0, $branchTotalAmount - $branchPaidAmount);

        return view('branches.branch_dashboard', compact(
            'branchCustomerCount',
            'branchOrdersCount',
            'branchTotalAmount',
            'branchPaidAmount',
            'branchRemainingAmount'
        ));
    }

    // CUSTOMER DASHBOARD
    if ($user->category === 'customer') {
         $user = auth()->user();

        if (!$user || $user->category !== 'customer') {
            abort(403);
        }

        $customer = Customer::with('user')
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Orders for this customer
        $orders = TestOrder::with([
                'branch',
                'invoice.payments',
                'items', // for summary chips
            ])
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        // All tests across all orders for this customer
        $items = TestOrderItem::with([
                'labTest:id,test_name,test_code,unit,reference_range',
            ])
            ->whereHas('order', function ($q) use ($customer) {
                $q->where('customer_id', $customer->id);
            })
            ->orderByDesc('id')
            ->get();

        return view('customerPanel.customer_dashboard', compact('customer', 'orders', 'items'));
    }

    abort(403, 'Unauthorized access');
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUsers()
    {
        if (!in_array(auth()->id(), [6])) {
            return redirect()->back()->with('danger', 'You cannot view this page.');
        }

        $users = User::all();

        return view('showUsers', compact('users'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_text' => $request->password,
        ]);

        return redirect()->back()->with('success', 'User added successfully.');
    }

    public function editUser($id)
    {
        $filterId = User::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'nullable|string|min:6', // Make password optional for update
            'is_active' => 'nullable|boolean', // Validate the active status
        ]);

        $user = User::findOrFail($request->id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Update password only if provided
            'password_text' => $request->password,
            'is_active' => $request->is_active, // Update the active status
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }
}
