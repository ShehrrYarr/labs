<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Slip #{{ $order->id }}</title>

    <style>
        @page { margin: 18px; }
        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111;
            background: #fff;
        }

        .logo-wrap { text-align:center; margin-bottom: 10px; }
        .logo { width: 260px; max-width: 100%; height: auto; }

        .copy {
            border: 1px solid #111;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 14px;
        }

        .copy-title {
            font-weight: 900;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: .6px;
            margin-bottom: 8px;
        }

        .row { display: table; width: 100%; }
        .col { display: table-cell; vertical-align: top; width: 50%; }

        .meta { width: 100%; border-collapse: collapse; }
        .meta td { padding: 2px 0; vertical-align: top; }
        .label { font-weight: 800; width: 110px; }

        .tbl {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .tbl th {
            text-align: left;
            border-bottom: 1px solid #111;
            padding: 6px 4px;
            font-size: 12px;
        }
        .tbl td {
            padding: 6px 4px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        .right { text-align:right; }
        .muted { color:#444; font-size:11px; }

        .summary { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .summary td { padding: 2px 0; }

        .status {
            margin-top: 8px;
            text-align: center;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .divider-cut { border-top: 2px dashed #111; margin: 18px 0; }
    </style>
</head>
<body>

@php
    $customer = $order->customer;
    $cu = $customer?->user;
    $status = strtoupper($invoice->status ?? 'unpaid');
@endphp

<div class="logo-wrap">
    @php $slipLogoB64 = $setting->logoBase64(); @endphp
    @if($slipLogoB64)
        <img class="logo" src="{{ $slipLogoB64 }}" alt="Logo">
    @else
        <div style="font-weight:900;font-size:18px;">{{ $setting->lab_name }}</div>
    @endif
    <div style="font-size:11px;color:#374151;margin-top:3px;">{{ $setting->lab_name }}</div>
    @if($setting->lab_address)
        <div style="font-size:10px;color:#555;">{{ $setting->lab_address }}</div>
    @endif
    @if($setting->lab_phone || $setting->lab_email)
        <div style="font-size:10px;color:#555;">
            @if($setting->lab_phone){{ $setting->lab_phone }}@endif
            @if($setting->lab_phone && $setting->lab_email) &nbsp;|&nbsp; @endif
            @if($setting->lab_email){{ $setting->lab_email }}@endif
        </div>
    @endif
</div>

{{-- CUSTOMER COPY --}}
<div class="copy">
    <div class="copy-title">Customer Copy</div>

    <div class="row">
        <div class="col">
            <table class="meta">
                <tr><td class="label">Panel:</td><td>{{ $labName ?? 'Al Ghani Labs' }}</td></tr>
                <tr><td class="label">Lab #:</td><td>{{ str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
                <tr><td class="label">Created:</td><td>{{ optional($order->created_at)->format('d-m-Y h:i A') }}</td></tr>
            </table>
        </div>
        <div class="col">
            <table class="meta">
                <tr><td class="label">Customer:</td><td>{{ $cu?->name ?? '-' }}</td></tr>
                <tr><td class="label">Age:</td><td>{{ $customer?->display_age ?? '-' }}</td></tr>
                <tr><td class="label">Phone:</td><td>{{ $customer?->phone ?? '-' }}</td></tr>
            </table>
        </div>
    </div>

    {{-- ✅ ONLY billing lines (Test Type + Price) --}}
    <table class="tbl">
        <thead>
        <tr>
            <th>Test Type</th>
            {{-- <th class="right" style="width:90px;">Code</th> --}}
            <th class="right" style="width:110px;">Price</th>
        </tr>
        </thead>
        <tbody>
        @forelse($types as $tp)
            <tr>
                <td>{{ $tp['name'] }}</td>
                {{-- <td class="right">{{ $tp['code'] }}</td> --}}
                <td class="right">{{ number_format((float)$tp['price'], 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="muted">No test types found for this order.</td></tr>
        @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr><td class="right muted">Subtotal:</td><td class="right" style="width:130px;">{{ number_format($subtotal, 2) }}</td></tr>
        <tr><td class="right muted">Discount:</td><td class="right">{{ number_format($discount, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Total:</td><td class="right" style="font-weight:900;">{{ number_format($total, 2) }}</td></tr>
        <tr><td class="right muted">Paid:</td><td class="right">{{ number_format($paid, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Balance:</td><td class="right" style="font-weight:900;">{{ number_format($remaining, 2) }}</td></tr>
    </table>

    <div class="status">{{ $status }}</div>

    @if(!empty($loginId) && !empty($password))
        <div style="margin-top:10px;border-top:1px dashed #bbb;padding-top:8px;">
            <div style="font-weight:900;font-size:12px;margin-bottom:4px;">Customer Login Details</div>
            <table class="meta">
                <tr><td class="label">Login ID:</td><td style="font-weight:900;">{{ $loginId }}</td></tr>
                <tr><td class="label">Password:</td><td style="font-weight:900;">{{ $password }}</td></tr>
            </table>
        </div>
    @endif
</div>

<div class="divider-cut"></div>

{{-- LAB COPY --}}
<div class="copy">
    <div class="copy-title">Lab Copy</div>

    <div class="row">
        <div class="col">
            <table class="meta">
                               <tr><td class="label">Lab #:</td><td>{{ str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
                <tr><td class="label">Created:</td><td>{{ optional($order->created_at)->format('d-m-Y h:i A') }}</td></tr>
                <tr><td class="label">Ref By:</td><td>{{ $customer?->ref_by ?? '-' }}</td></tr>
            </table>
        </div>
        <div class="col">
            <table class="meta">
                <tr><td class="label">Customer:</td><td>{{ $cu?->name ?? '-' }}</td></tr>
                <tr><td class="label">Age:</td><td>{{ $customer?->display_age ?? '-' }}</td></tr>
                <tr><td class="label">Phone:</td><td>{{ $customer?->phone ?? '-' }}</td></tr>
                <tr><td class="label">Branch:</td><td>{{ $order->branch?->branch_name ?? 'Main' }}</td></tr>
            </table>
        </div>
    </div>

    <table class="tbl">
        <thead>
        <tr>
            <th>Test Type</th>
            {{-- <th class="right" style="width:90px;">Code</th> --}}
            <th class="right" style="width:110px;">Price</th>
        </tr>
        </thead>
        <tbody>
        @forelse($types as $tp)
            <tr>
                <td>{{ $tp['name'] }}</td>
                {{-- <td class="right">{{ $tp['code'] }}</td> --}}
                <td class="right">{{ number_format((float)$tp['price'], 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="muted">No test types found for this order.</td></tr>
        @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr><td class="right muted">Subtotal:</td><td class="right" style="width:130px;">{{ number_format($subtotal, 2) }}</td></tr>
        <tr><td class="right muted">Discount:</td><td class="right">{{ number_format($discount, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Total:</td><td class="right" style="font-weight:900;">{{ number_format($total, 2) }}</td></tr>
        <tr><td class="right muted">Paid:</td><td class="right">{{ number_format($paid, 2) }}</td></tr>
        <tr><td class="right" style="font-weight:900;">Balance:</td><td class="right" style="font-weight:900;">{{ number_format($remaining, 2) }}</td></tr>
    </table>

    <div class="status">{{ $status }}</div>
</div>

</body>
</html>
