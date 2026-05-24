@php
    use Illuminate\Support\Carbon;

    $logoB64 = $setting->logoBase64();

    $customerUser = $order->customer->user;

    $patientName = $customerUser->name ?? '-';
    $gender = $order->customer->gender ?? '-';
    $age = $order->customer?->display_age ?? '-';

    $regDate = $order->created_at
        ? Carbon::parse($order->created_at)->format('d-m-Y h:i A')
        : '-';

    $kindRank = function ($kind) {
        $k = strtolower((string)$kind);
        if ($k === 'main') return 0;
        if ($k === 'sub')  return 1;
        return 2;
    };

    $typesMap = [];
    if (isset($types) && $types) {
        foreach ($types as $tp) $typesMap[$tp->id] = $tp->name;
    }

    $itemsByType = $order->items->groupBy(fn($it) => $it->test_type_id ?? 0);

    $itemsByType = $itemsByType->sortBy(function($items, $typeId) use ($typesMap) {
        $name = $items->first()?->testType?->name ?? ($typesMap[$typeId] ?? ('Type #'.$typeId));
        return strtolower((string)$name);
    });
@endphp

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Order Report #{{ $order->id }}</title>

<style>
@page { margin: 0; }

body {
    margin: 0;
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 11px;
    color: #111827;
}

/* One type = one page */
.page {
    page-break-after: always;
    position: relative;
    min-height: 100%;
}
.page:last-child { page-break-after: auto; }

.page-header {
    position: absolute;
    top: 0; left: 0; right: 0;
    padding: 14px 45px 10px;
    border-bottom: 1px solid rgba(17,24,39,.3);
}
.header-lab-name { font-size:16px; font-weight:900; color:#111827; }
.header-detail { font-size:9.5px; color:#374151; margin-top:2px; }

.content {
    padding: 130px 45px 210px 45px;
}

/* Footer */
.footer-wrap {
    position: absolute;
    left: 45px;
    right: 45px;
    bottom: 18px;
}

.footer-note {
    text-align: center;
    font-size: 9px;
    color: #374151;
    margin-bottom: 5px;
}

.footer-divider {
    height: 1px;
    background: rgba(17,24,39,.25);
    margin-bottom: 5px;
}

.footer-doctors {
    font-size: 8.8px;
}

.footer-grid { width:100%; border-collapse:collapse; }
.footer-grid td {
    width: 20%;
    vertical-align: top;
    padding-right: 5px;
}

.doctor-name { font-weight: 800; }
.doctor-desc {
    font-size: 8.4px;
    color: #374151;
    line-height: 1.2;
}

/* Meta */
.meta-table { width:100%; border-collapse:collapse; }
.meta-table td { padding: 2px 0; }

.label { font-weight:700; width:110px; }
.hr { height:1px; background:#111827; opacity:.35; margin:8px 0 10px; }

/* Titles */
.type-title {
    font-size: 16px;
    font-weight: 900;
    margin: 0 0 6px;
}

.section-title {
    font-size: 13px;
    font-weight: 800;
    margin: 10px 0 6px;
}

/* Table (COMPACT) */
table.report {
    width:100%;
    border-collapse:collapse;
    font-size: 10.2px;
}

table.report th {
    text-align:left;
    padding: 5px 6px;
    border-bottom:1px solid rgba(17,24,39,.35);
    font-size: 10px;
}

table.report td {
    padding: 4px 6px;
    border-bottom:1px solid rgba(17,24,39,.12);
    vertical-align: top;
}

.processing {
    color:#9ca3af;
    font-style:italic;
}
</style>
</head>

<body>

@foreach($itemsByType as $typeId => $typeItems)
@php
    $typeName = $typeItems->first()?->testType?->name ?? ($typesMap[$typeId] ?? ('Type #'.$typeId));

    $grouped = $typeItems->groupBy(function ($it) use ($typeName) {
        $category = $it->labTest?->testCategory?->name;
        if (!$category && strtoupper(trim($typeName)) === 'CBC') {
            return 'Diff. Leuc Count (DLC)';
        }
        return $category ?? 'Other';
    });
@endphp

<div class="page">
<div class="page-header">
    <table style="width:100%;border-collapse:collapse;">
        <tr>
            @if($logoB64)
            <td style="width:75px;vertical-align:middle;padding-right:10px;">
                <img src="{{ $logoB64 }}" style="max-width:75px;max-height:65px;width:auto;height:auto;" alt="Logo">
            </td>
            @endif
            <td style="vertical-align:middle;text-align:center;">
                <div class="header-lab-name">{{ $setting->lab_name }}</div>
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
            </td>
        </tr>
    </table>
</div>
<div class="content">

<table class="meta-table">
<tr>
<td>
    <div><span class="label">Patient:</span> {{ $patientName }}</div>
    <div><span class="label">Panel:</span> {{ $labName }}</div>
    <div><span class="label">Order No:</span> {{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>

    <div><span class="label">Ref By:</span> {{ $order->customer->ref_by ?? '-' }}</div>
</td>
<td>
    <div><span class="label">Age / Sex:</span> {{ $age }} / {{ ucfirst($gender) }}</div>
    <div><span class="label">Reg Date:</span> {{ $regDate }}</div>
    <div><span class="label">Report Time:</span> {{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}</div>
    <div><span class="label">Phone:</span> {{ $order->customer->phone ?? '-' }}</div>
</td>
</tr>
</table>

<div class="hr"></div>

<div class="type-title">{{ $typeName }}</div>

@foreach($grouped as $categoryName => $items)
@php
    $sortedItems = $items->sort(function($a, $b) use ($kindRank) {
        $ka = $kindRank($a->item_kind ?? '');
        $kb = $kindRank($b->item_kind ?? '');
        if ($ka !== $kb) return $ka <=> $kb;

        $sa = (int)($a->sort_order_snapshot ?? 0);
        $sb = (int)($b->sort_order_snapshot ?? 0);
        if ($sa !== $sb) return $sa <=> $sb;

        return (int)$a->id <=> (int)$b->id;
    });
@endphp

<div class="section-title">{{ $categoryName }}</div>

<table class="report">
<thead>
<tr>
    <th style="width:38%">Test</th>
    <th style="width:20%">Result</th>
    <th style="width:18%">Unit</th>
    <th style="width:24%">Reference Range</th>
</tr>
</thead>
<tbody>
@foreach($sortedItems as $it)
@php
    $unit = $it->unit_snapshot ?? $it->labTest?->unit ?? '';
    $range = $it->reference_range_snapshot ?? $it->labTest?->reference_range ?? '';
@endphp
<tr>
    <td>{{ $it->test_name_snapshot }}</td>
    <td>
        @if($it->result_status === 'ready')
            {{ $it->result_text ?: '—' }}
            @if(!empty($it->result_notes))
                <div style="font-size:10px;color:#555;margin-top:3px;white-space:pre-wrap;"><em>{{ $it->result_notes }}</em></div>
            @endif
        @else
            <span class="processing">Processing</span>
        @endif
    </td>
    <td>{{ $unit }}</td>
    <td>{{ $range }}</td>
</tr>
@endforeach
</tbody>
</table>
@endforeach

</div>

<div class="footer-wrap">
<div class="footer-note">{{ $setting->footer_note }}</div>
<div class="footer-divider"></div>
@if(!empty($setting->doctors))
<div class="footer-doctors">
<table class="footer-grid">
<tr>
@foreach($setting->doctors as $doc)
<td><div class="doctor-name">{{ $doc['name'] }}</div><div class="doctor-desc">{!! nl2br(e($doc['description'] ?? '')) !!}</div></td>
@endforeach
</tr>
</table>
</div>
@endif
</div>

</div>
@endforeach

</body>
</html>
