<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thermal Receipt</title>

    <style>
        @page { margin: 10px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
        }

        .center { text-align: center; }
        .bold { font-weight: 800; }
        .right { text-align: right; }
        .muted { color: #444; }
        .small { font-size: 9px; }

        .line {
            border-top: 1px dashed #111;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .status {
            font-size: 11px;
            font-weight: 900;
            margin-top: 4px;
        }

        /* Logo safe for DomPDF */
        .logo-wrap {
            text-align: center;
            margin-bottom: 6px;
        }

        .logo-img {
            max-width: 220px;
            width: 100%;
            height: auto;
            display: inline-block;
        }
    </style>
</head>
<body>

@php
    $status = strtoupper($invoice->status ?? 'unpaid');
@endphp

<div class="logo-wrap">
    @php $thermalLogoB64 = $setting->logoBase64(); @endphp
    @if($thermalLogoB64)
        <img class="logo-img" src="{{ $thermalLogoB64 }}" alt="{{ $labName }}">
    @else
        <div class="bold" style="font-size:13px;">{{ $labName }}</div>
    @endif
    <div class="bold" style="font-size:11px;margin-top:2px;">{{ $labName }}</div>
    @if($setting->lab_address)
        <div class="small muted">{{ $setting->lab_address }}</div>
    @endif
    @if($setting->lab_phone)
        <div class="small muted">{{ $setting->lab_phone }}</div>
    @endif
    @if($setting->lab_email)
        <div class="small muted">{{ $setting->lab_email }}</div>
    @endif
    <div class="small muted">Thermal Receipt</div>
</div>

<div class="line"></div>

<table>
    <tr>
        <td class="muted">Lab #</td>
        <td class="right bold">{{ $order->id }}</td>
    </tr>
    <tr>
        <td class="muted">Reg Date</td>
        <td class="right">{{ optional($order->created_at)->format('d-m-Y h:i A') }}</td>
    </tr>
    <tr>
        <td class="muted">Report Time</td>
        <td class="right">{{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}</td>
    </tr>
    <tr>
        <td class="muted">Customer</td>
        <td class="right">{{ $order->customer?->user?->name ?? '-' }}</td>
    </tr>
    <tr>
        <td class="muted">Phone</td>
        <td class="right">{{ $order->customer?->phone ?? '-' }}</td>
    </tr>
</table>

<div class="line"></div>

<div class="bold">Test Types</div>

<table>
    @forelse($types as $tp)
        <tr>
            <td style="width:70%;">
                {{ $tp['name'] }}
                <div class="small muted">{{ $tp['code'] }}</div>
            </td>
            <td class="right" style="width:30%;">
                {{ number_format((float)$tp['price'], 2) }}
            </td>
        </tr>
    @empty
        <tr>
            <td class="muted">No billing items found.</td>
        </tr>
    @endforelse
</table>

<div class="line"></div>

<table>
    <tr>
        <td class="muted">Subtotal</td>
        <td class="right">{{ number_format($subtotal, 2) }}</td>
    </tr>
    <tr>
        <td class="muted">Discount</td>
        <td class="right">-{{ number_format($discount, 2) }}</td>
    </tr>
    <tr>
        <td class="bold">Total</td>
        <td class="right bold">{{ number_format($total, 2) }}</td>
    </tr>
    <tr>
        <td class="muted">Paid</td>
        <td class="right">{{ number_format($paid, 2) }}</td>
    </tr>
    <tr>
        <td class="bold">Remaining</td>
        <td class="right bold">{{ number_format($remaining, 2) }}</td>
    </tr>
</table>

<div class="line"></div>

<div class="center status">STATUS: {{ $status }}</div>

<div class="line"></div>

<div class="center small muted">
    Computer generated receipt
</div>

@if(!empty($loginId) && !empty($password))
    <div class="line"></div>

    <div class="center bold" style="margin-top:6px;">
        Customer Login Details
    </div>

    <table>
        <tr>
            <td class="muted">Login ID</td>
            <td class="right bold">{{ $loginId }}</td>
        </tr>
        <tr>
            <td class="muted">Password</td>
            <td class="right bold">{{ $password }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center small muted">
        Please keep this receipt safe
    </div>
@endif

</body>
</html>
