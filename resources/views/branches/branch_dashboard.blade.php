@extends('branches.branch_navbar')
@section('content')

<style>
    .dash-wrap{padding:18px}
    .hero{
        background: radial-gradient(900px 240px at 10% 0%, rgba(37,99,235,.18), transparent 60%),
                    radial-gradient(900px 240px at 90% 0%, rgba(245,158,11,.16), transparent 60%),
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
    .span-4{grid-column:span 4}
    .span-6{grid-column:span 6}
    .span-12{grid-column:span 12}
    @media(max-width:1100px){.span-4{grid-column:span 6}.span-6{grid-column:span 12}}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .bar{height:10px;background:#eef2ff;border-radius:999px;overflow:hidden;margin-top:10px}
    .bar > div{height:100%;background:linear-gradient(135deg,#2563eb,#1e40af);width:0%}
    .money{font-variant-numeric:tabular-nums}
</style>

@php
    $total = (float)($branchTotalAmount ?? 0);
    $paid  = (float)($branchPaidAmount ?? 0);
    $rem   = (float)($branchRemainingAmount ?? 0);
    $pct   = $total > 0 ? round(($paid / $total) * 100) : 0;
@endphp
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
<div class="dash-wrap">
    <div class="hero">
        <div>
            <div class="pill">Branch Dashboard</div>
            <h2 style="margin-top:10px;">Branch Performance</h2>
            <p>Your customers, orders, and billing summary.</p>
        </div>
        <div class="pill">
            <span>Orders:</span>
            <span style="font-size:18px;font-weight:950;">{{ $branchOrdersCount ?? 0 }}</span>
        </div>
    </div>

    <div class="grid">
        <div class="card span-4">
            <div class="label">Customers (Created by Branch)</div>
            <div class="value">{{ $branchCustomerCount ?? 0 }}</div>
            <div class="sub">Customers registered by this branch</div>
        </div>

        <div class="card span-4">
            <div class="label">Orders (Branch)</div>
            <div class="value">{{ $branchOrdersCount ?? 0 }}</div>
            <div class="sub">Total orders created from branch visits</div>
        </div>

        <div class="card span-4">
            <div class="label">Payment Progress</div>
            <div class="value">{{ $pct }}%</div>
            <div class="sub">Paid vs billed</div>
        </div>

        <div class="card span-4">
            <div class="label">Total Amount (Branch Orders)</div>
            <div class="value money">{{ number_format($total, 2) }}</div>
            <div class="sub">Billed total (after discounts)</div>
        </div>

        <div class="card span-4">
            <div class="label">Paid Amount</div>
            <div class="value money">{{ number_format($paid, 2) }}</div>
            <div class="sub">Collected so far</div>
        </div>

        <div class="card span-4">
            <div class="label">Remaining Amount</div>
            <div class="value money">{{ number_format($rem, 2) }}</div>
            <div class="sub">Pending payments</div>
        </div>

        <div class="card span-12">
            <div class="label">Collection Bar</div>
            <div class="bar">
                <div style="width: {{ $pct }}%;"></div>
            </div>
            <div class="sub">A quick visual of collections</div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
