<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TestOrder;
use App\Models\TestOrderItem;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $cat = auth()->user()->category ?? null;
            if (!in_array($cat, ['admin', 'branch'], true)) {
                abort(403, 'Only admin or branch can access customer orders.');
            }
            return $next($request);
        });
    }

    public function index(Customer $customer)
{
    $this->authorizeCustomerAccess($customer);

    // Orders + assigned items (each item is an independent test row: main/sub)
    $orders = TestOrder::with([
            'items' => function ($q) {
                $q->orderBy('sort_order_snapshot')
                  ->orderBy('id');
            },
            'items.resultPostedByUser:id,name',
            'invoice.payments',
            'branch',
            'createdByUser',
        ])
        ->where('customer_id', $customer->id)
        ->latest()
        ->get();

    // Types -> Tests -> Subtests for "Assign by type" preview + auto add
    $types = TestType::where('is_active', true)
        ->with(['labTests' => function ($q) {
            $q->where('is_active', true)
              ->orderBy('test_name')
              ->with(['subTests' => function ($sq) {
                  $sq->where('is_active', true)
                     ->orderBy('sort_order')
                     ->orderBy('test_name');
              }]);
        }])
        ->orderBy('name')
        ->get();

    // Clean array for JS (avoid Blade parsing issues)
    $typesForJs = $types->map(function ($tp) {
        return [
            'id'    => $tp->id,
            'name'  => $tp->name,
            'price' => (float) ($tp->price ?? 0),
            'tests' => $tp->labTests->map(function ($t) {
                return [
                    'id'              => $t->id,
                    'name'            => $t->test_name,
                    'code'            => $t->test_code,
                    'unit'            => $t->unit,
                    'reference_range' => $t->reference_range,
                    'sort_order'      => (int) ($t->sort_order ?? 0),
                    'subtests'        => ($t->subTests ?? collect())->map(function ($s) {
                        return [
                            'id'              => $s->id,
                            'name'            => $s->test_name,
                            'code'            => $s->test_code,
                            'unit'            => $s->unit,
                            'reference_range' => $s->reference_range,
                            'sort_order'      => (int) ($s->sort_order ?? 0),
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    })->values();

    if (auth()->user()->category === 'branch') {
        return view('branches.customers.tests', compact('customer', 'orders', 'typesForJs'));
    }

    return view('customers.tests', compact('customer', 'orders', 'typesForJs'));
}

    /**
     * ✅ NEW: storeItems now only accepts test_type_id.
     * It will add:
     * - 1 charge row (price = type price)
     * - all tests (price = 0)
     * - all subtests (price = 0)
     */
    public function storeItems(Request $request, Customer $customer, TestOrder $order)
{
    $this->authorizeOrderAccess($customer, $order);

    $data = $request->validate([
        'test_type_id' => ['required', 'integer', 'exists:test_types,id'],
    ]);

    $authId = auth()->id();

    $type = TestType::with(['labTests' => function ($q) {
        $q->where('is_active', true)
          ->orderBy('test_name')
          ->with(['subTests' => function ($sq) {
              $sq->where('is_active', true)
                 ->orderBy('sort_order')
                 ->orderBy('test_name');
          }]);
    }])->findOrFail($data['test_type_id']);

    DB::transaction(function () use ($order, $type, $authId) {

        $typePrice = (float)($type->price ?? 0);

        foreach ($type->labTests as $t) {
            // ✅ main test row
            TestOrderItem::updateOrCreate(
                [
                    'test_order_id' => $order->id,
                    'lab_test_id'   => $t->id,
                ],
                [
                    'test_type_id'          => $type->id,
                    'type_price_snapshot'   => $typePrice,

                    'lab_sub_test_id'       => null,
                    'test_category_id'      => $t->test_category_id ?? null,
                    'assigned_by_user_id'   => $authId,

                    'item_kind'             => 'main',

                    'test_name_snapshot'    => $t->test_name,
                    'test_code_snapshot'    => $t->test_code,
                    'unit_snapshot'         => $t->unit,
                    'reference_range_snapshot' => $t->reference_range,
                    'sort_order_snapshot'   => 0,

                    'result_status'         => 'pending',
                    'result_text'           => null,
                    'result_file'           => null,
                    'result_posted_at'      => null,
                    'result_posted_by_user_id' => null,
                ]
            );

            // ✅ sub test rows (independent, also editable)
            foreach (($t->subTests ?? collect()) as $s) {
                TestOrderItem::updateOrCreate(
                    [
                        'test_order_id'   => $order->id,
                        'lab_sub_test_id' => $s->id,
                    ],
                    [
                        'test_type_id'          => $type->id,
                        'type_price_snapshot'   => $typePrice,

                        'lab_test_id'           => null, // keep independent as you want
                        'test_category_id'      => $s->test_category_id ?? null,
                        'assigned_by_user_id'   => $authId,

                        'item_kind'             => 'sub',

                        'test_name_snapshot'    => $s->test_name,
                        'test_code_snapshot'    => $s->test_code,
                        'unit_snapshot'         => $s->unit,
                        'reference_range_snapshot' => $s->reference_range,
                        'sort_order_snapshot'   => (int)($s->sort_order ?? 0),

                        'result_status'         => 'pending',
                        'result_text'           => null,
                        'result_file'           => null,
                        'result_posted_at'      => null,
                        'result_posted_by_user_id' => null,
                    ]
                );
            }
        }

        $order->update(['status' => 'tests_assigned']);
        $this->recalculateInvoice($order);
    });

    return redirect()
        ->route('customers.tests', $customer)
        ->with('success', 'Type assigned: all tests and subtests added as independent result rows.');
}

    /**
     * ✅ Results are stored directly on test_order_items now
     */
    public function postResult(Request $request, Customer $customer, TestOrder $order, TestOrderItem $item)
    {
        if (auth()->user()->category !== 'admin') {
            abort(403, 'Only admin can post results.');
        }

        $this->authorizeOrderAccess($customer, $order);

        if ($item->test_order_id !== $order->id) {
            abort(404);
        }

        // Don't allow results on charge row
        if ($item->item_kind === 'charge') {
            return redirect()
                ->route('customers.tests', $customer)
                ->with('success', 'Charge row has no results.');
        }

        $data = $request->validate([
            'result_text'   => ['nullable', 'string', 'max:10000'],
            'result_status' => ['required', 'in:pending,processing,ready'],
        ]);

        $item->update([
            'result_text'              => $data['result_text'] ?? null,
            'result_status'            => $data['result_status'],
            'result_posted_at'         => now(),
            'result_posted_by_user_id' => auth()->id(),
        ]);

        // If all non-charge items are ready -> results_posted
        $order->load('items');

        $nonCharge = $order->items->where('item_kind', '!=', 'charge');
        $allReady = $nonCharge->count() > 0 && $nonCharge->every(fn($i) => $i->result_status === 'ready');

        $order->update([
            'status' => $allReady ? 'results_posted' : 'in_progress'
        ]);

        return redirect()
            ->route('customers.tests', $customer)
            ->with('success', 'Result saved successfully.');
    }

  private function recalculateInvoice(TestOrder $order): void
{
    $order->loadMissing(['items', 'invoice.payments']);

    $invoice = Invoice::firstOrCreate(['test_order_id' => $order->id]);

    // ✅ Charge once per test type (CBC, PCR etc.)
    $typeIds = $order->items
        ->pluck('test_type_id')
        ->filter()
        ->unique()
        ->values();

    $typePrices = \App\Models\TestType::whereIn('id', $typeIds)->pluck('price', 'id');

    $subtotal = 0.0;
    foreach ($typeIds as $tid) {
        $subtotal += (float) ($typePrices[$tid] ?? 0);
    }

    $discountType  = $invoice->discount_type ?? 'none';
    $discountValue = (float) ($invoice->discount_value ?? 0);

    $discountAmount = 0.0;

    if ($discountType === 'percent') {
        // Guard percent
        if ($discountValue > 100) $discountValue = 100;
        $discountAmount = ($subtotal * $discountValue) / 100.0;
    } elseif ($discountType === 'flat') {
        $discountAmount = $discountValue;
    }

    // prevent over-discount
    if ($discountAmount > $subtotal) $discountAmount = $subtotal;

    $total = $subtotal - $discountAmount;

    $paid = (float) \App\Models\Payment::where('invoice_id', $invoice->id)->sum('amount');

    $status = 'unpaid';
    if ($paid <= 0) $status = 'unpaid';
    elseif ($paid > 0 && $paid < $total) $status = 'partial';
    else $status = 'paid';

    $invoice->update([
        'subtotal'        => $subtotal,
        'discount_amount' => $discountAmount,
        'total_amount'    => $total,
        'paid_amount'     => $paid,
        'status'          => $status,
    ]);
}



    // ---- keep your authorize helpers same ----
    private function authorizeCustomerAccess(Customer $customer): void
    {
        $user = auth()->user();
        if ($user->category === 'admin') return;

        $branchId = optional($user->branch)->id;
        if (!$branchId || $customer->created_by_branch_id !== $branchId) {
            abort(403, 'You do not have permission to access this customer.');
        }
    }

    public function storeOrder(Request $request, Customer $customer)
{
    $this->authorizeCustomerAccess($customer);

    $data = $request->validate([
        'visited_at' => ['nullable', 'date'],
        'notes'      => ['nullable', 'string', 'max:2000'],
    ]);

    $user = auth()->user();

    $order = TestOrder::create([
        'customer_id'        => $customer->id,
        'branch_id'          => $user->category === 'branch'
                                ? optional($user->branch)->id
                                : null,
        'created_by_user_id' => $user->id,
        'status'             => 'created',
        'visited_at'         => $data['visited_at'] ?? now(),
        'notes'              => $data['notes'] ?? null,
    ]);

    // Ensure invoice exists immediately
    Invoice::firstOrCreate([
        'test_order_id' => $order->id,
    ]);

    return redirect()
        ->route('customers.tests', $customer)
        ->with('success', 'New order created successfully.');
}

public function updateDiscount(Request $request, Customer $customer, TestOrder $order)
{
    $this->authorizeOrderAccess($customer, $order);

    $data = $request->validate([
        'discount_type'  => ['required', 'in:none,percent,flat'],
        'discount_value' => ['nullable', 'numeric', 'min:0'],
    ]);

    $invoice = Invoice::firstOrCreate(['test_order_id' => $order->id]);

    $discountType = $data['discount_type'];
    $discountValue = (float) ($data['discount_value'] ?? 0);

    if ($discountType === 'none') {
        $discountValue = 0;
    }

    $invoice->update([
        'discount_type'  => $discountType,
        'discount_value' => $discountValue,
    ]);

    $this->recalculateInvoice($order);

    return redirect()
        ->route('customers.tests', $customer)
        ->with('success', 'Discount updated.');
}
public function storePayment(Request $request, Customer $customer, TestOrder $order)
{
    $this->authorizeOrderAccess($customer, $order);

    $data = $request->validate([
        'amount' => ['required', 'numeric', 'min:0.01'],
        'method' => ['required', 'in:cash,card,online,other'],
        'notes'  => ['nullable', 'string', 'max:1000'],
    ]);

    DB::transaction(function () use ($data, $order) {
        $invoice = Invoice::firstOrCreate([
            'test_order_id' => $order->id
        ]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $data['amount'],
            'method' => $data['method'],
            'received_by_user_id' => auth()->id(),
            'paid_at' => now(),
            'notes' => $data['notes'] ?? null,
        ]);

        // 🔁 Recalculate totals after payment
        $this->recalculateInvoice($order);
    });

    return redirect()
        ->route('customers.tests', $customer)
        ->with('success', 'Payment added successfully.');
}

public function testHistory(\App\Models\Customer $customer)
{
    $this->authorizeCustomerAccess($customer);

    $items = \App\Models\TestOrderItem::with([
            'order:id,customer_id,created_at',
            'labTest:id,test_name,test_code',
            'subTest:id,lab_test_id,test_name,test_code',
            'subTest.parentTest:id,test_name,test_code',
        ])
        ->whereHas('order', function ($q) use ($customer) {
            $q->where('customer_id', $customer->id);
        })
        // ✅ Your actual values are main/sub
        ->whereIn('item_kind', ['main', 'sub'])
        ->orderByDesc('id')
        ->get();

    if (auth()->user()->category === 'branch') {
        return view('branches.customers.test_history', compact('customer', 'items'));
    }

    return view('customers.test_history', compact('customer', 'items'));
}





    private function authorizeOrderAccess(Customer $customer, TestOrder $order): void
    {
        $this->authorizeCustomerAccess($customer);

        if ($order->customer_id !== $customer->id) {
            abort(404);
        }
    }
}
