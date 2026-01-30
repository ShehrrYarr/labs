@extends('admin_navbar')
@section('content')

<style>
    .dash-wrap{padding:18px}
    .hero{
        background: radial-gradient(900px 240px at 10% 0%, rgba(37,99,235,.18), transparent 60%),
                    radial-gradient(900px 240px at 90% 0%, rgba(16,185,129,.16), transparent 60%),
                    linear-gradient(135deg,#0f172a,#111827);
        color:#fff;border-radius:16px;padding:18px 18px;
        box-shadow:0 12px 30px rgba(0,0,0,.22);
        animation:fadeIn .5s ease;
        display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;align-items:center
    }
    .hero h2{margin:0;font-weight:950;letter-spacing:.2px}
    .hero p{margin:6px 0 0;color:rgba(255,255,255,.82)}
    .pill{display:inline-flex;gap:8px;align-items:center;background:rgba(255,255,255,.12);padding:8px 12px;border-radius:999px;font-weight:800}
    .grid{display:grid;grid-template-columns:repeat(12,1fr);gap:12px;margin-top:14px}
    .card{
        background:#fff;border-radius:14px;padding:14px 14px;
        box-shadow:0 10px 25px rgba(0,0,0,.08);
        border:1px solid #eef2f7;
        transition:transform .2s ease, box-shadow .2s ease;
        position:relative;overflow:hidden;
        animation:fadeIn .55s ease;
    }
    .card:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(0,0,0,.12)}
    .card::before{
        content:"";position:absolute;inset:0;
        background:radial-gradient(700px 220px at 0% 0%, rgba(37,99,235,.10), transparent 60%);
        pointer-events:none
    }
    .label{color:#64748b;font-size:12px;font-weight:900;margin-bottom:6px}
    .value{color:#0f172a;font-size:24px;font-weight:950;line-height:1}
    .sub{color:#475569;font-size:12px;margin-top:8px}
    .span-3{grid-column:span 3}
    .span-4{grid-column:span 4}
    .span-6{grid-column:span 6}
    .span-12{grid-column:span 12}
    @media(max-width:1100px){.span-3{grid-column:span 6}.span-4{grid-column:span 6}.span-6{grid-column:span 12}}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .bar{
        height:10px;background:#eef2ff;border-radius:999px;overflow:hidden;margin-top:10px
    }
    .bar > div{
        height:100%;
        background:linear-gradient(135deg,#2563eb,#1e40af);
        width:0%
    }
    .money{font-variant-numeric:tabular-nums}
</style>
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
<div class="dash-wrap">
    <div class="hero">
        <div>
            <div class="pill">Admin Dashboard</div>
            <h2 style="margin-top:10px;">Overview & System Stats</h2>
            <p>Quick snapshot of tests, branches, customers, and billing.</p>
        </div>
        <div class="pill">
            <span>Orders:</span>
            <span style="font-size:18px;font-weight:950;">{{ $ordersCount ?? 0 }}</span>
        </div>
    </div>

    <div class="grid">
        {{-- Core entities --}}
        <div class="card span-3">
            <div class="label">Test Types</div>
            <div class="value">{{ $testTypeCount ?? 0 }}</div>
            <div class="sub">Total test types configured</div>
        </div>

        <div class="card span-3">
            <div class="label">Equipment</div>
            <div class="value">{{ $equipmentCount ?? 0 }}</div>
            <div class="sub">Equipment entries</div>
        </div>

        <div class="card span-3">
            <div class="label">Test Categories</div>
            <div class="value">{{ $categoryCount ?? 0 }}</div>
            <div class="sub">Categories (e.g., Chemical Parameters)</div>
        </div>

        <div class="card span-3">
            <div class="label">Lab Tests</div>
            <div class="value">{{ $labTestsCount ?? 0 }}</div>
            <div class="sub">Total tests available</div>
        </div>

        {{-- Users/branches --}}
        <div class="card span-4">
            <div class="label">Overall Customers</div>
            <div class="value">{{ $customerCount ?? 0 }}</div>
            <div class="sub">All customers in the system</div>
        </div>

        <div class="card span-4">
            <div class="label">Overall Branches</div>
            <div class="value">{{ $branchCount ?? 0 }}</div>
            <div class="sub">Active branches</div>
        </div>

        <div class="card span-4">
            <div class="label">Overall Orders</div>
            <div class="value">{{ $ordersCount ?? 0 }}</div>
            <div class="sub">Orders/visits created</div>
        </div>

        {{-- Billing --}}
        @php
            $total = (float)($totalAmount ?? 0);
            $paid  = (float)($paidAmount ?? 0);
            $rem   = (float)($remainingAmount ?? 0);
            $pct   = $total > 0 ? round(($paid / $total) * 100) : 0;
        @endphp

        <div class="card span-4">
            <div class="label">Overall Order Amount</div>
            <div class="value money">{{ number_format($total, 2) }}</div>
            <div class="sub">Total billed amount (after discounts)</div>
        </div>

        <div class="card span-4">
            <div class="label">Overall Paid Amount</div>
            <div class="value money">{{ number_format($paid, 2) }}</div>
            <div class="sub">Collected payments</div>
        </div>

        <div class="card span-4">
            <div class="label">Overall Remaining Amount</div>
            <div class="value money">{{ number_format($rem, 2) }}</div>
            <div class="sub">Pending amount</div>
        </div>

        <div class="card span-12">
            <div class="label">Payment Progress</div>
            <div class="value">{{ $pct }}%</div>
            <div class="bar">
                <div style="width: {{ $pct }}%;"></div>
            </div>
            <div class="sub">Paid vs total billed</div>
        </div>
    </div>
</div>

<div class="card span-12" style="margin-top:12px;">
    <div class="label">Unfinished Orders</div>

    <table style="width:100%;border-collapse:collapse;margin-top:10px;background:#fff;border-radius:12px;overflow:hidden">
        <thead>
            <tr style="background:#f1f5f9;">
                <th style="text-align:left;padding:12px;font-size:13px;color:#334155;">Order #</th>
                <th style="text-align:left;padding:12px;font-size:13px;color:#334155;">Customer</th>
                <th style="text-align:left;padding:12px;font-size:13px;color:#334155;">Branch</th>
                <th style="text-align:left;padding:12px;font-size:13px;color:#334155;">Created</th>
                <th style="text-align:left;padding:12px;font-size:13px;color:#334155;">Pending Tests</th>
                <th style="text-align:left;padding:12px;font-size:13px;color:#334155;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unfinishedOrders ?? [] as $o)
                @php
                    $pendingCount = $o->items->whereIn('result_status', ['pending','processing'])->count();
                    $customerName = $o->customer?->user?->name ?? '—';
                    $branchName = $o->branch?->branch_name ?? 'Main/Admin';
                @endphp

                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="padding:12px;">#{{ $o->id }}</td>
                    <td style="padding:12px;">{{ $customerName }}</td>
                    <td style="padding:12px;">{{ $branchName }}</td>
                    <td style="padding:12px;">{{ $o->created_at?->format('Y-m-d H:i') }}</td>
                    <td style="padding:12px;">
                        <span class="badge b-partial">{{ $pendingCount }}</span>
                    </td>
                    <td style="padding:12px;">
                        <a class="btn btn-ghost mini-btn"
                           href="{{ route('customers.tests', ['customer' => $o->customer_id]) }}?open_order={{ $o->id }}">
                            Open Order
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:14px;color:#64748b;">No unfinished orders 🎉</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>
</div>

<script>
    
</script>
@endsection
