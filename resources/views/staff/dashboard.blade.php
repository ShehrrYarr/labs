@extends('staff_navbar')
@section('content')
<style>
    .dash-wrap{padding:18px}
    .hero{
        background:linear-gradient(135deg,#0f172a,#1e3a5f);
        color:#fff;border-radius:16px;padding:20px 22px;
        box-shadow:0 12px 30px rgba(0,0,0,.22);
        display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;align-items:center
    }
    .hero h2{margin:0;font-weight:950;letter-spacing:.2px}
    .hero p{margin:6px 0 0;color:rgba(255,255,255,.82)}
    .pill{display:inline-flex;gap:8px;align-items:center;background:rgba(255,255,255,.12);padding:8px 12px;border-radius:999px;font-weight:800}
    .grid{display:grid;grid-template-columns:repeat(12,1fr);gap:12px;margin-top:14px}
    .stat-card{
        grid-column:span 3;background:#fff;border-radius:14px;padding:16px;
        box-shadow:0 6px 20px rgba(0,0,0,.07);border:1px solid #eef2f7;
    }
    @media(max-width:900px){.stat-card{grid-column:span 6;}}
    @media(max-width:600px){.stat-card{grid-column:span 12;}}
    .stat-label{font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
    .stat-value{font-size:26px;font-weight:950;color:#0f172a}
    .stat-sub{font-size:12px;color:#64748b;margin-top:3px}
    .table-wrap{background:#fff;border-radius:14px;padding:16px;box-shadow:0 6px 20px rgba(0,0,0,.07);border:1px solid #eef2f7;margin-top:14px}
    .badge-status{display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:900}
    .badge-pending{background:#fef3c7;color:#92400e}
    .badge-processing{background:#dbeafe;color:#1e40af}
    .badge-created{background:#f1f5f9;color:#475569}
    @keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
    .dash-wrap > *{animation:fadeIn .5s ease}
</style>

<div class="app-content content">
<div class="content-wrapper">
<div class="content-body">
<div class="dash-wrap">

    <!-- Hero -->
    <div class="hero">
        <div>
            <h2>Welcome, {{ Auth::user()->name }}</h2>
            <p>Staff Panel &mdash; {{ now()->format('l, d M Y') }}</p>
        </div>
        <div>
            <span class="pill"><i class="feather icon-user"></i> Staff</span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid" style="margin-top:16px">
        <div class="stat-card">
            <div class="stat-label">Total Customers</div>
            <div class="stat-value">{{ number_format($customerCount) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($ordersCount) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Billed</div>
            <div class="stat-value" style="font-size:20px">PKR {{ number_format($totalAmount, 0) }}</div>
            <div class="stat-sub">Paid: PKR {{ number_format($paidAmount, 0) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Outstanding</div>
            <div class="stat-value" style="font-size:20px;color:#dc2626">PKR {{ number_format($remainingAmount, 0) }}</div>
        </div>
    </div>

    <!-- Permissions chips -->
    <div style="margin-top:14px;background:#fff;border-radius:14px;padding:14px 16px;box-shadow:0 6px 20px rgba(0,0,0,.07);border:1px solid #eef2f7;">
        <div style="font-size:12px;font-weight:900;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px">Your Permissions</div>
        <div style="display:flex;flex-wrap:wrap;gap:8px">
            @php
                $perms = [
                    'create_customers' => ['Create Customers','feather icon-user-plus'],
                    'create_orders'    => ['Create Orders','feather icon-clipboard'],
                    'assign_tests'     => ['Assign Tests','feather icon-list'],
                    'enter_results'    => ['Enter Results','feather icon-edit-2'],
                    'manage_payments'  => ['Manage Payments','feather icon-credit-card'],
                    'print_reports'    => ['Print Reports','feather icon-printer'],
                    'price_calculator' => ['Price Calculator','feather icon-dollar-sign'],
                    'delete_tests'           => ['Remove Tests','feather icon-x-circle'],
                    'delete_orders'          => ['Delete Orders','feather icon-trash-2'],
                    'manage_test_types'      => ['Test Types','feather icon-tag'],
                    'manage_test_categories' => ['Test Categories','feather icon-folder'],
                    'manage_lab_tests'       => ['Lab Tests','feather icon-activity'],
                    'manage_equipment'       => ['Equipment','feather icon-tool'],
                ];
            @endphp
            @foreach($perms as $key => [$label, $icon])
                @if(Auth::user()->hasStaffPermission($key))
                    <span style="display:inline-flex;align-items:center;gap:6px;background:#ecfdf5;color:#065f46;padding:6px 12px;border-radius:999px;font-size:12px;font-weight:800">
                        <i class="{{ $icon }}" style="font-size:12px"></i> {{ $label }}
                    </span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:6px;background:#f1f5f9;color:#94a3b8;padding:6px 12px;border-radius:999px;font-size:12px;font-weight:700;text-decoration:line-through">
                        {{ $label }}
                    </span>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="table-wrap">
        <div style="font-size:15px;font-weight:900;color:#0f172a;margin-bottom:12px">
            Pending / In-Progress Orders
            <span style="background:#fef3c7;color:#92400e;font-size:12px;font-weight:900;padding:3px 10px;border-radius:999px;margin-left:8px">{{ $unfinishedOrders->count() }}</span>
        </div>
        @if($unfinishedOrders->isEmpty())
            <p style="color:#64748b;font-size:13px">No pending orders.</p>
        @else
        <div style="overflow-x:auto">
        <table class="table table-hover" style="font-size:13px">
            <thead>
                <tr style="background:#f8fafc">
                    <th>Order #</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Tests</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unfinishedOrders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>{{ $order->customer?->user?->name ?? '—' }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge-status badge-{{ $order->status === 'created' ? 'created' : ($order->status === 'in_progress' ? 'processing' : 'pending') }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td>{{ $order->items->count() }}</td>
                    <td>
                        @if($order->customer)
                        <a href="{{ route('customers.tests', $order->customer) }}" class="btn btn-sm btn-primary" style="font-size:12px;padding:4px 10px">
                            View
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>

</div>
</div>
</div>
</div>
@endsection
