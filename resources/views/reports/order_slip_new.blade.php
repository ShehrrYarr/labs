<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Slip #{{ $order->id }}</title>

    <style>
        @page { margin: 12px 15mm 12px 20mm; }
        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #111;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 7px;
            border-bottom: 1px solid #999;
        }
        .header-logo { max-width: 180px; max-height: 55px; height: auto; display: block; margin: 0 auto 3px; }
        .header-name  { font-size: 14px; font-weight: 900; }
        .header-detail { font-size: 9px; color: #555; }

        .copy-block { width: 100%; }
        .copy-divider { border: none; border-top: 2px dashed #999; margin: 12px 0; }

        .copy-title {
            font-weight: 900;
            text-transform: uppercase;
            font-size: 10.5px;
            letter-spacing: .5px;
            margin-bottom: 5px;
            border-bottom: 1px solid #111;
            padding-bottom: 3px;
        }

        .meta { width: 100%; border-collapse: collapse; }
        .meta td { padding: 1px 0; vertical-align: top; font-size: 10px; }
        .label { font-weight: 800; width: 78px; }

        .tbl { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .tbl th { text-align: left; border-bottom: 1px solid #111; padding: 3px 2px; font-size: 10px; }
        .tbl td { padding: 3px 2px; border-bottom: 1px solid #ddd; font-size: 10px; }

        .right { text-align: right; }
        .muted { color: #444; }

        .summary { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .summary td { padding: 1px 0; font-size: 10px; }

        .status { margin-top: 5px; text-align: center; font-weight: 900; font-size: 12px; letter-spacing: 1px; }

        .login-box { margin-top: 5px; border-top: 1px dashed #bbb; padding-top: 4px; }
        .login-title { font-weight: 900; font-size: 10px; margin-bottom: 2px; }
    </style>
</head>
<body>

@php
    $customer = $order->customer;
    $cu       = $customer?->user;
    $status   = strtoupper($invoice->status ?? 'unpaid');
    $slipLogoB64      = $setting->logoBase64();
    $slipCompositeB64 = $setting->headerCompositeBase64();
@endphp

{{-- Header --}}
<div class="header">
    @if($slipCompositeB64)
        <img src="{{ $slipCompositeB64 }}" style="width:100%;display:block;max-height:120px;object-fit:contain;" alt="Header">
    @else
        @if($slipLogoB64)
            <img class="header-logo" src="{{ $slipLogoB64 }}" alt="Logo">
        @endif
        <div class="header-name">{{ $setting->lab_name }}</div>
        @if($setting->lab_address)
            <div class="header-detail">{{ $setting->lab_address }}</div>
        @endif
        @if($setting->lab_phone || $setting->lab_email)
            <div class="header-detail">
                @if($setting->lab_phone){{ $setting->lab_phone }}@endif
                @if($setting->lab_phone && $setting->lab_email) &nbsp;|&nbsp; @endif
                @if($setting->lab_email){{ $setting->lab_email }}@endif
            </div>
        @endif
    @endif
</div>

{{-- ── CUSTOMER COPY (top) ── --}}
<div class="copy-block">
    <div class="copy-title">Customer Copy</div>

    <table class="meta">
        <tr><td class="label">Panel:</td>       <td>{{ $labName ?? $setting->lab_name }}</td></tr>
        <tr><td class="label">Lab #:</td>       <td>{{ str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
        <tr><td class="label">Reg Date:</td>    <td>{{ optional($order->created_at)->format('d-m-Y h:i A') }}</td></tr>
        <tr><td class="label">Report Time:</td> <td>{{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}</td></tr>
        <tr><td class="label">Customer:</td>    <td>{{ $cu?->name ?? '-' }}</td></tr>
        <tr><td class="label">Age:</td>         <td>{{ $customer?->display_age ?? '-' }}</td></tr>
        <tr><td class="label">Phone:</td>       <td>{{ $customer?->phone ?? '-' }}</td></tr>
    </table>

    <table class="tbl">
        <thead>
            <tr>
                <th>Test Type</th>
                <th class="right" style="width:80px;">Price</th>
            </tr>
        </thead>
        <tbody>
        @forelse($types as $tp)
            <tr>
                <td>{{ $tp['name'] }}</td>
                <td class="right">{{ number_format((float)$tp['price'], 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="2" class="muted">No tests found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr><td class="right muted">Subtotal:</td><td class="right" style="width:85px;">{{ number_format($subtotal, 2) }}</td></tr>
        <tr><td class="right muted">Discount:</td><td class="right">{{ number_format($discount, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Total:</td><td class="right" style="font-weight:900;">{{ number_format($total, 2) }}</td></tr>
        <tr><td class="right muted">Paid:</td><td class="right">{{ number_format($paid, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Balance:</td><td class="right" style="font-weight:900;">{{ number_format($remaining, 2) }}</td></tr>
    </table>

    <div class="status">{{ $status }}</div>

    @if(!empty($loginId) && !empty($password))
        <div class="login-box">
            <div class="login-title">Customer Login Details</div>
            <table class="meta">
                <tr><td class="label">Login ID:</td><td style="font-weight:900;">{{ $loginId }}</td></tr>
                <tr><td class="label">Password:</td><td style="font-weight:900;">{{ $password }}</td></tr>
            </table>
        </div>
    @endif
</div>

{{-- Dashed horizontal divider --}}
<hr class="copy-divider">

{{-- ── LAB COPY (bottom) ── --}}
<div class="copy-block">
    <div class="copy-title">Lab Copy</div>

    <table class="meta">
        <tr><td class="label">Panel:</td>       <td>{{ $labName ?? $setting->lab_name }}</td></tr>
        <tr><td class="label">Lab #:</td>       <td>{{ str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
        <tr><td class="label">Reg Date:</td>    <td>{{ optional($order->created_at)->format('d-m-Y h:i A') }}</td></tr>
        <tr><td class="label">Report Time:</td> <td>{{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}</td></tr>
        <tr><td class="label">Customer:</td>    <td>{{ $cu?->name ?? '-' }}</td></tr>
        <tr><td class="label">Age:</td>         <td>{{ $customer?->display_age ?? '-' }}</td></tr>
        <tr><td class="label">Phone:</td>       <td>{{ $customer?->phone ?? '-' }}</td></tr>
        <tr><td class="label">Ref By:</td>      <td>{{ $customer?->ref_by ?? '-' }}</td></tr>
        <tr><td class="label">Branch:</td>      <td>{{ $order->branch?->branch_name ?? 'Main' }}</td></tr>
    </table>

    <table class="tbl">
        <thead>
            <tr>
                <th>Test Type</th>
                <th class="right" style="width:80px;">Price</th>
            </tr>
        </thead>
        <tbody>
        @forelse($types as $tp)
            <tr>
                <td>{{ $tp['name'] }}</td>
                <td class="right">{{ number_format((float)$tp['price'], 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="2" class="muted">No tests found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr><td class="right muted">Subtotal:</td><td class="right" style="width:85px;">{{ number_format($subtotal, 2) }}</td></tr>
        <tr><td class="right muted">Discount:</td><td class="right">{{ number_format($discount, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Total:</td><td class="right" style="font-weight:900;">{{ number_format($total, 2) }}</td></tr>
        <tr><td class="right muted">Paid:</td><td class="right">{{ number_format($paid, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Balance:</td><td class="right" style="font-weight:900;">{{ number_format($remaining, 2) }}</td></tr>
    </table>

    <div class="status">{{ $status }}</div>
</div>

</body>
</html>
