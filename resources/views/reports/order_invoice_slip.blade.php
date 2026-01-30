<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Slip</title>
    <style>
        @page { margin: 22px 28px; }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color:#111827;
        }

        /* Letterhead image at top */
        .letterhead{
            position: fixed;
            top: -10px;
            left: 0;
            right: 0;
            height: 160px;
            z-index: -1;
        }
        .letterhead img{
            width: 100%;
            height: 160px;
            object-fit: contain;
        }

        .content{
            margin-top: 140px; /* push content below letterhead */
        }

        .title{
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .grid{
            width:100%;
            margin-top: 8px;
        }
        .grid td{
            padding: 4px 0;
            vertical-align: top;
        }
        .muted{ color:#6b7280; }

        .badge{
            display:inline-block;
            padding:4px 10px;
            border-radius:999px;
            font-size:11px;
            font-weight:800;
        }
        .paid{ background:#dcfce7; color:#166534; }
        .partial{ background:#fef9c3; color:#854d0e; }
        .unpaid{ background:#fee2e2; color:#991b1b; }

        table.items{
            width:100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        table.items th{
            background:#f3f4f6;
            text-align:left;
            padding:8px;
            font-size:12px;
        }
        table.items td{
            padding:8px;
            border-bottom:1px solid #e5e7eb;
        }

        .totals{
            width: 100%;
            margin-top: 12px;
        }
        .totals td{
            padding: 4px 0;
        }
        .right{text-align:right;}
        .strong{font-weight:800;}
        .footer{
            margin-top: 16px;
            font-size: 11px;
            color:#6b7280;
        }
    </style>
</head>
<body>

    <div class="letterhead">
        <img src="file://{{ $letterheadPath }}" alt="letterhead">
    </div>

    @php
        $status = $invoice->status ?? 'unpaid';
        $badgeClass = $status === 'paid' ? 'paid' : ($status === 'partial' ? 'partial' : 'unpaid');
        $discountAmount = (float) ($invoice->discount_amount ?? 0);
        $subtotal = (float) ($invoice->subtotal ?? 0);
    @endphp

    <div class="content">
        <div class="title">Patient Invoice Slip</div>

        <table class="grid">
            <tr>
                <td style="width:55%;">
                    <div><span class="muted">Lab:</span> <b>{{ $labName }}</b></div>
                    <div><span class="muted">Customer:</span> <b>{{ $order->customer?->user?->name ?? '-' }}</b></div>
                    <div><span class="muted">Email:</span> {{ $order->customer?->user?->email ?? '-' }}</div>
                    <div><span class="muted">Phone:</span> {{ $order->customer?->phone ?? '-' }}</div>
                </td>
                <td class="right">
                    <div><span class="muted">Order #</span> <b>{{ $order->id }}</b></div>
                    <div><span class="muted">Created:</span> {{ optional($order->created_at)->format('Y-m-d h:i A') }}</div>
                    <div style="margin-top:6px;">
                        <span class="badge {{ $badgeClass }}">{{ strtoupper($status) }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Test</th>
                    <th style="width:120px;">Code</th>
                    <th style="width:120px;" class="right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $it)
                    <tr>
                        <td>{{ $it->test_name_snapshot }}</td>
                        <td>{{ $it->test_code_snapshot }}</td>
                        <td class="right">{{ number_format((float)$it->price_snapshot, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td class="right muted">Subtotal:</td>
                <td class="right" style="width:160px;">{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="right muted">Discount:</td>
                <td class="right">- {{ number_format($discountAmount, 2) }}</td>
            </tr>
            <tr>
                <td class="right strong">Total:</td>
                <td class="right strong">{{ number_format($total, 2) }}</td>
            </tr>
            <tr>
                <td class="right muted">Paid:</td>
                <td class="right">{{ number_format($paid, 2) }}</td>
            </tr>
            <tr>
                <td class="right strong">Remaining:</td>
                <td class="right strong">{{ number_format($remaining, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            This is a computer-generated invoice slip.
        </div>
    </div>
</body>
</html>
