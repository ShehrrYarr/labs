<?php

namespace App\Http\Controllers;

use App\Models\LabSetting;
use App\Models\TestOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TestType;

class OrderReportController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    



// public function single(\App\Models\TestOrder $order)
// {
//     $user = auth()->user();

//     if (!in_array($user->category, ['admin', 'branch', 'customer'], true)) {
//         abort(403);
//     }

//     $order->load([
//         'customer.user',
//         'branch',
//         'items' => function ($q) {
//             $q->leftJoin('lab_tests', 'lab_tests.id', '=', 'test_order_items.lab_test_id')
//               ->orderBy('lab_tests.sort_order')   // ✅ MAIN SORT
//               ->orderBy('test_order_items.id')    // ✅ fallback
//               ->select('test_order_items.*');
//         },
//         'items.labTest.testCategory',
//         'items.testType',
//         'items.resultPostedByUser',
//     ]);

//     // Branch restriction
//     if ($user->category === 'branch') {
//         $branchId = optional($user->branch)->id;
//         if (!$branchId || $order->customer->created_by_branch_id !== $branchId) {
//             abort(403);
//         }
//     }

//     // Customer restriction
//     if ($user->category === 'customer') {
//         if (($order->customer->user_id ?? null) !== $user->id) {
//             abort(403);
//         }
//     }

//     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.order_single', [
//         'order' => $order,
//         'labName' => $order->branch?->branch_name ?? null,
//         'letterheadPath' => public_path('letterheads/report_letterhead.png'),
//     ])->setPaper('a4');

//     return $pdf->stream('order-'.$order->id.'-report.pdf');
// }

public function single(\App\Models\TestOrder $order, Request $request)
{
    $user   = auth()->user();
    $layout = $request->input('layout', 'full');
    $showHeader = in_array($layout, ['full', 'header']);
    $showFooter = in_array($layout, ['full', 'footer']);

    // if (!in_array($user->category, ['admin', 'branch', 'customer'], true)) {
    //     abort(403);
    // }

    $order->load([
        'customer.user',
        'branch',
        'items' => function ($q) {
            $q->leftJoin('lab_tests', 'lab_tests.id', '=', 'test_order_items.lab_test_id')
              ->leftJoin('lab_sub_tests', 'lab_sub_tests.id', '=', 'test_order_items.lab_sub_test_id')

              ->orderByRaw("
                  CASE
                    WHEN test_order_items.item_kind IN ('sub', 'subtest') THEN 1
                    ELSE 0
                  END
              ")
              ->orderByRaw("COALESCE(lab_tests.sort_order, 999999)")
              ->orderByRaw("COALESCE(lab_sub_tests.sort_order, 999999)")
              ->orderBy('test_order_items.id')
              ->select('test_order_items.*');
        },
        'items.labTest.testCategory',
        'items.testType',
        'items.resultPostedByUser',
    ]);

    // // Branch restriction
    // if ($user->category === 'branch') {
    //     $branchId = optional($user->branch)->id;
    //     if (!$branchId || $order->customer->created_by_branch_id !== $branchId) {
    //         abort(403);
    //     }
    // }

    // Customer restriction
    if ($user->category === 'customer') {
        if (($order->customer->user_id ?? null) !== $user->id) {
            abort(403);
        }
    }

    $setting = LabSetting::instance();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.order_single', [
        'order'          => $order,
        'labName'        => $order->branch?->branch_name ?? $setting->lab_name,
        'letterheadPath' => public_path('letterheads/report_letterhead.png'),
        'setting'        => $setting,
        'showHeader'     => $showHeader,
        'showFooter'     => $showFooter,
    ])->setPaper('a4');

    return $pdf->stream('order-'.$order->id.'-report.pdf');
}




//     public function customerAll(\App\Models\Customer $customer)
// {
//     $user = auth()->user();

//     if (!in_array($user->category, ['admin', 'branch'], true)) {
//         abort(403);
//     }

//     // Branch can only view its own customers
//     if ($user->category === 'branch') {
//         $branchId = optional($user->branch)->id;
//         if (!$branchId || $customer->created_by_branch_id !== $branchId) {
//             abort(403);
//         }
//     }

//     // Load all orders + items for this customer
//     $orders = \App\Models\TestOrder::with([
//             'branch',
//             'items.labTest.testCategory',
//             'items.resultPostedByUser',
//             'invoice',
//         ])
//         ->where('customer_id', $customer->id)
//         ->orderBy('id', 'asc')
//         ->get();

//     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.customer_all_orders', [
//         'customer' => $customer->load('user'),
//         'orders' => $orders,
//         'letterheadPath' => public_path('letterheads/report_letterhead.png'),
//         'mainLabName' => config('app.name'),
//     ])->setPaper('a4');

//     return $pdf->stream('customer-'.$customer->id.'-all-orders.pdf');
// }

public function customerAll(\App\Models\Customer $customer, Request $request)
{
    $user   = auth()->user();
    $layout = $request->input('layout', 'full');
    $showHeader = in_array($layout, ['full', 'header']);
    $showFooter = in_array($layout, ['full', 'footer']);

    // Allow admin, branch, and customer
    if (!in_array($user->category, ['admin', 'branch', 'customer'], true)) {
        abort(403);
    }

    // Branch can only view its own customers
    if ($user->category === 'branch') {
        $branchId = optional($user->branch)->id;
        if (!$branchId || $customer->created_by_branch_id !== $branchId) {
            abort(403);
        }
    }

    // Customer can only download their own history
    if ($user->category === 'customer') {
        if (($customer->user_id ?? null) !== $user->id) {
            abort(403);
        }
    }

    // Load all orders + items for this customer
    $orders = \App\Models\TestOrder::with([
            'branch',
            'items.labTest.testCategory',
            'items.resultPostedByUser',
            'invoice.payments',
        ])
        ->where('customer_id', $customer->id)
        ->orderBy('id', 'asc')
        ->get();

    $setting = LabSetting::instance();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.customer_all_orders', [
        'customer'       => $customer->load('user'),
        'orders'         => $orders,
        'letterheadPath' => public_path('letterheads/report_letterhead.png'),
        'mainLabName'    => $setting->lab_name,
        'setting'        => $setting,
        'showHeader'     => $showHeader,
        'showFooter'     => $showFooter,
    ])->setPaper('a4');

    return $pdf->stream('customer-'.$customer->id.'-all-orders.pdf');
}

// public function invoiceSlip(\App\Models\TestOrder $order)
// {
//     $user = auth()->user();
//     if (!$user) abort(401);

//     if (!in_array($user->category, ['admin', 'branch', 'customer'], true)) {
//         abort(403);
//     }

//     $order->load([
//         'customer.user',
//         'branch',
//         'items',
//         'invoice.payments',
//     ]);

//     // Branch can only view its own customers' orders
//     if ($user->category === 'branch') {
//         $branchId = optional($user->branch)->id;
//         if (!$branchId || ($order->customer?->created_by_branch_id ?? null) !== $branchId) {
//             abort(403);
//         }
//     }

//     // Customer can only view their own orders
//     if ($user->category === 'customer') {
//         if (($order->customer?->user_id ?? null) !== $user->id) {
//             abort(403);
//         }
//     }

//     $inv = $order->invoice;
//     $total = (float) ($inv->total_amount ?? 0);
//     $paid  = (float) ($inv->paid_amount ?? 0);
//     $remaining = max(0, $total - $paid);

//     $letterheadPath = public_path('letterheads/patient_invoice_letterhead.png');

//     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.order_invoice_slip', [
//         'order' => $order,
//         'invoice' => $inv,
//         'total' => $total,
//         'paid' => $paid,
//         'remaining' => $remaining,
//         'letterheadPath' => $letterheadPath,
//         'labName' => $order->branch?->branch_name ?? config('app.name'),
//     ])->setPaper('a4');

//     return $pdf->stream('order-'.$order->id.'-slip.pdf');
// }

public function invoiceSlip(TestOrder $order)
{
    $user = auth()->user();
    if (!$user) abort(401);

    // if (!in_array($user->category, ['admin', 'branch', 'customer'], true)) {
    //     abort(403);
    // }

    $order->load([
        'customer.user',
        'branch',
        'items:id,test_order_id,test_type_id',
        'invoice.payments',
    ]);

    // Branch restriction
    // if ($user->category === 'branch') {
    //     $branchId = optional($user->branch)->id;
    //     if (!$branchId || ($order->customer?->created_by_branch_id ?? null) !== $branchId) {
    //         abort(403);
    //     }
    // }

    // Customer restriction
    if ($user->category === 'customer') {
        if (($order->customer?->user_id ?? null) !== $user->id) {
            abort(403);
        }
    }

    $invoice = $order->invoice;

    $subtotal  = (float) ($invoice->subtotal ?? 0);
    $discount  = (float) ($invoice->discount_amount ?? 0);
    $total     = (float) ($invoice->total_amount ?? 0);
    $paid      = (float) ($invoice->paid_amount ?? 0);
    $remaining = max(0, $total - $paid);

    // ✅ UNIQUE test types used in this order
    $typeIds = $order->items
        ->pluck('test_type_id')
        ->filter()
        ->unique()
        ->values();

    $types = TestType::whereIn('id', $typeIds)
        ->orderBy('name')
        ->get()
        ->map(function ($tp) {
            return [
                'name'  => $tp->name,
                'code'  => $tp->code ?? $tp->id, // fallback if no code column
                'price' => (float)($tp->price ?? 0),
            ];
        });

    $logoPath = public_path('letterheads/thermal_logo.png');

    $loginId  = $order->customer?->user?->login_id ?? null;
    $password = $order->customer?->user?->password_text ?? null;
    $setting  = LabSetting::instance();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.order_slip_new', [
        'order'     => $order,
        'invoice'   => $invoice,
        'subtotal'  => $subtotal,
        'discount'  => $discount,
        'total'     => $total,
        'paid'      => $paid,
        'remaining' => $remaining,
        'logoPath'  => $setting->logoPath() ?? $logoPath,
        'labName'   => $order->branch?->branch_name ?? $setting->lab_name,
        'types'     => $types,
        'loginId'   => $loginId,
        'password'  => $password,
        'setting'   => $setting,
    ])->setPaper('a4');

    return $pdf->stream('order-'.$order->id.'-slip.pdf');
}

public function thermalReceipt(TestOrder $order)
{
    $user = auth()->user();
    if (!$user) abort(401);

    // if (!in_array($user->category, ['admin', 'branch', 'customer'], true)) {
    //     abort(403);
    // }

    $order->load([
        'customer.user',
        'branch',
        'items:id,test_order_id,test_type_id',
        'invoice.payments',
    ]);

    // Branch restriction
    // if ($user->category === 'branch') {
    //     $branchId = optional($user->branch)->id;
    //     if (!$branchId || ($order->customer?->created_by_branch_id ?? null) !== $branchId) {
    //         abort(403);
    //     }
    // }

    // Customer restriction
    if ($user->category === 'customer') {
        if (($order->customer?->user_id ?? null) !== $user->id) {
            abort(403);
        }
    }

    $invoice = $order->invoice;

    $subtotal  = (float) ($invoice->subtotal ?? 0);
    $discount  = (float) ($invoice->discount_amount ?? 0);
    $total     = (float) ($invoice->total_amount ?? 0);
    $paid      = (float) ($invoice->paid_amount ?? 0);
    $remaining = max(0, $total - $paid);

    // ✅ Unique test types in this order (billing lines)
    $typeIds = $order->items
        ->pluck('test_type_id')
        ->filter()
        ->unique()
        ->values();

    $types = TestType::whereIn('id', $typeIds)
        ->orderBy('name')
        ->get()
        ->map(function ($tp) {
            return [
                'name'  => $tp->name,
                'code'  => $tp->code ?? $tp->id, // fallback if no `code` column
                'price' => (float)($tp->price ?? 0),
            ];
        });

    // ✅ Credentials
    $loginId  = $order->customer?->user?->login_id ?? null;
    $password = $order->customer?->user?->password_text ?? null;

    $setting  = LabSetting::instance();
    $logoPath = $setting->logoPath() ?? public_path('letterheads/thermal_logo.png');

    // 80mm thermal width
    $paperWidthPt  = 226.77; // 80mm
    $paperHeightPt = 1100;   // enough for long receipts

    $pdf = Pdf::loadView('reports.order_thermal_receipt', [
        'order'     => $order,
        'invoice'   => $invoice,
        'subtotal'  => $subtotal,
        'discount'  => $discount,
        'total'     => $total,
        'paid'      => $paid,
        'remaining' => $remaining,
        'labName'   => $order->branch?->branch_name ?? $setting->lab_name,
        'logoPath'  => $logoPath,
        'setting'   => $setting,

        // billing lines
        'types'     => $types,

        // credentials
        'loginId'   => $loginId,
        'password'  => $password,
    ])->setPaper([0, 0, $paperWidthPt, $paperHeightPt]);

    return $pdf->stream('order-'.$order->id.'-thermal-receipt.pdf');
}

}
