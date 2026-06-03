{{-- resources/views/reports/order_single.blade.php --}}

@php
    use Illuminate\Support\Carbon;

    $logoB64        = $setting->logoBase64();
    $compositeB64   = $setting->headerCompositeBase64();
    $watermarkB64   = $setting->watermarkCompositeBase64();

    // Bottom notice
    $bottomNotice = $setting->bottom_notice ?? '';
    $bnStyle      = $setting->bottom_notice_style ?? [];
    $bnColor      = $bnStyle['color']  ?? '#1d4ed8';
    $bnSize       = (int)($bnStyle['size']  ?? 10);
    $bnAlign      = $bnStyle['align']  ?? 'right';
    $bnItalic     = $bnStyle['italic'] ?? true;
    $bnBold       = $bnStyle['bold']   ?? false;

    $customerUser = $order->customer->user;

    $patientName = $customerUser->name ?? '-';
    $gender = $order->customer->gender ?? '-';
    $age = $order->customer?->display_age ?? '-';

    $regDate = $order->created_at
        ? Carbon::parse($order->created_at)->format('d-m-Y h:i A')
        : '-';

    // Map type_id => type name (fallback)
    $typesMap = [];
    if (isset($types) && $types) {
        foreach ($types as $tp) $typesMap[$tp->id] = $tp->name;
    }

    /**
     * IMPORTANT:
     * We MUST preserve "pure creation order" exactly as inserted.
     * So we DO NOT use:
     * - sortBy / sortKeys / ksort
     * - groupBy that might reorder keys (it keeps insertion for values, but keys order can change if you later sort)
     *
     * We build:
     * 1) Type groups in the order their first item appears
     * 2) Category groups in the order their first item appears
     * 3) Rows in the order they appear in $order->items
     */

    // 1) Build type groups in insertion order
    $itemsByType = [];
    foreach ($order->items as $it) {
        $tid = $it->test_type_id ?? 0;
        if (!isset($itemsByType[$tid])) $itemsByType[$tid] = collect();
        $itemsByType[$tid]->push($it);
    }
@endphp

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Report #{{ $order->id }}</title>

    <style>
        @page { margin: 0; background-color: #dce9f7; }

        html {
            background-color: #dce9f7;
        }

        body{
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #111827;
            background-color: #dce9f7;
        }

        /* One type = one page */
        .page{
            page-break-after: always;
            position: relative;
            min-height: 100%;
            z-index: 1;
        }

        .page-header{
            position: absolute;
            top: 0; left: 0; right: 0;
            padding: 14px 45px 10px;
            border-bottom: 1px solid rgba(17,24,39,.3);
        }
        .header-lab-name{ font-size:16px; font-weight:900; color:#111827; }
        .header-detail{ font-size:9.5px; color:#374151; margin-top:2px; }
        .range-cell{
      white-space: pre-line;   /* keeps \n line breaks */
      line-height: 1.1;        /* tight spacing */
      margin: 0;
      padding: 0;
  }
        .page:last-child{ page-break-after: auto; }

        .content{
            padding: 130px 45px 210px 45px; /* overridden inline per page */
        }

        /* ===== FOOTER (PER PAGE, NO OVERLAP) ===== */
        .footer-wrap{
            position: absolute;
            left: 45px;
            right: 45px;
            bottom: 18px;
        }
        .footer-note{
            text-align: center;
            font-size: 9px;
            color: #374151;
            margin-bottom: 5px;
        }
        .footer-divider{
            height: 1px;
            background: rgba(17,24,39,.25);
            margin-bottom: 5px;
        }
        .footer-doctors{ font-size: 8.8px; color:#111827; }

        .footer-grid{ width:100%; border-collapse:collapse; }
        .footer-grid td{
            width: 20%;
            vertical-align: top;
            padding-right: 5px;
        }
        .doctor-name{ font-weight: 800; }
        .doctor-desc{
            font-size: 8.4px;
            color: #374151;
            line-height: 1.2;
        }

        /* Meta */
        .meta-table{ width:100%; border-collapse:collapse; }
        .meta-table td{ padding: 2px 0; vertical-align: top; }

        .label{ font-weight: 700; width: 110px; }
        .hr{ height:1px; background:#111827; opacity:.35; margin:8px 0 10px; }

        /* Titles */
        .type-title{
            font-size: 16px;
            font-weight: 900;
            margin: 0 0 6px;
        }

        .section-title{
            font-size: 13px;
            font-weight: 800;
            margin: 10px 0 6px;
        }

        /* Table (COMPACT) */
        table.report{
            width: 100%;
            border-collapse: collapse;
            font-size: 10.2px;
        }
        table.report th{
            text-align: left;
            padding: 5px 6px;
            border-bottom: 1px solid rgba(17,24,39,.35);
            font-size: 10px;
        }
        table.report td{
            padding: 4px 6px;
            border-bottom: 1px solid rgba(17,24,39,.12);
            vertical-align: top;
        }

        .processing{ color:#9ca3af; font-style: italic; }
    </style>
</head>

<body>

{{-- Page watermark — position:fixed repeats on every PDF page --}}
@if($watermarkB64)
<div style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:0; overflow:hidden;">
    <img src="{{ $watermarkB64 }}" style="width:100%; height:100%;" alt="">
</div>
@endif

@foreach($itemsByType as $typeId => $typeItems)
   @php
    $typeName = $typeItems->first()?->testType?->name
        ?? ($typesMap[$typeId] ?? ('Type #'.$typeId));

    // Normalize once (important)
    $normalizedType = strtoupper(trim((string) $typeName));

    // 2) Build category groups in insertion order (first-seen)
    $grouped = [];

    foreach ($typeItems as $it) {
        $category = $it->labTest?->testCategory?->name;

        // ✅ CBC special case
        if (!$category && $normalizedType === 'CBC') {
            $category = 'Diff. Leuc Count (DLC)';
        }

        // ✅ Urine R/E special case
        if (!$category && in_array($normalizedType, ['URINE R/E', 'URINE RE'], true)) {
            $category = 'Microscopic Analysis';
        }

        // Fallback
        $category = $category ?: 'Other';

        if (!isset($grouped[$category])) {
            $grouped[$category] = collect();
        }

        // keeps PURE inserted order
        $grouped[$category]->push($it);
    }
@endphp

    <div class="page">
        @if($showHeader ?? true)
        <div class="page-header">
            @if($compositeB64)
                <img src="{{ $compositeB64 }}" style="width:100%;display:block;" alt="Header">
            @else
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
            @endif
        </div>
        @endif {{-- /showHeader --}}
        @php
            // Always reserve the header area — when printing footer-only on
            // pre-printed letterhead the top space must still be left blank.
            $ptop    = '130px';
            $pbottom = ($showFooter ?? true) ? '210px' : '20px';
        @endphp
        <div class="content" style="padding: {{ $ptop }} 45px {{ $pbottom }} 45px;">

            {{-- Patient Meta --}}
            <table class="meta-table">
                <tr>
                    <td>
                        <div><span class="label">Patient:</span> {{ $patientName }}</div>
                        <div><span class="label">Panel:</span> {{ $labName ?? 'Al Ghani Labs' }}</div>
                        <div><span class="label">Lab No:</span> {{ str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) }}</div>
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

            {{-- Test Type Title --}}
            <div class="type-title">{{ $typeName }}</div>

            {{-- Categories (in first-seen order), and rows (pure inserted order) --}}
            @foreach($grouped as $categoryName => $items)
                <div class="section-title">{{ $categoryName }}</div>

                @if(in_array(strtoupper(trim($typeName)), ['URINE R/E', 'URINE RE'], true))
    <div class="small" style="margin:2px 0 6px;font-weight:700;color:#374151;">
        Physical And Chemical Analysis
    </div>
@endif

                <table class="report">
                    <thead>
                        <tr>
                            <th style="width:38%;">Test</th>
                            <th style="width:20%;">Result</th>
                            <th style="width:18%;">Unit</th>
                            <th style="width:24%;">Reference Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                            @php
                                $unit  = $it->unit_snapshot ?? $it->labTest?->unit ?? '';
                                // $range = $it->reference_range_snapshot ?? $it->labTest?->reference_range ?? '';
                                 $rangeText = (string)($it->reference_range_snapshot ?? $it->labTest?->reference_range ?? '');

    // if it was stored with <br> or <p>, convert to plain text safely
    $rangeText = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '<p>'], ["\n", "\n", "\n", "\n", ""], $rangeText));

    // normalize Windows newlines + remove extra blank lines
    $rangeText = str_replace("\r\n", "\n", $rangeText);
    $rangeText = preg_replace("/\n{3,}/", "\n\n", $rangeText); // prevent huge gaps
    $rangeText = trim($rangeText);
                            @endphp
                            @php
                                $testDesc = $it->subTest?->description ?? $it->labTest?->description ?? '';
                                $testDesc = trim(strip_tags($testDesc));
                            @endphp
                            <tr>
                                <td>
                                    {{ $it->test_name_snapshot }}
                                    @if($testDesc)
                                        <div style="font-size:9px;color:#6b7280;margin-top:2px;font-style:italic;line-height:1.3;">{{ $testDesc }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($it->result_status === 'ready')
                                        {{ $it->result_text ?: '—' }}
                                    @else
                                        <span class="processing">Processing</span>
                                    @endif
                                </td>
                                <td>{{ $unit }}</td>
                                {{-- <td style="white-space: pre-wrap; line-height:1.1;">
    {!! nl2br(e($range)) !!}
</td> --}}
<td><div class="range-cell">{{ $rangeText ?: '—' }}</div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach

            @php
                // Clinical notes entered during result entry
                $typeNote = '';
                foreach ($typeItems as $_ni) {
                    if (!empty($_ni->result_notes)) { $typeNote = $_ni->result_notes; break; }
                }
                // TestType description — preserve newlines, strip HTML, convert tabs to spaces
                $typeDesc = trim(strip_tags(
                    str_replace(['<br>','<br/>','<br />','</p>','</li>'], ["\n","\n","\n","\n","\n"],
                        (string)($typeItems->first()?->testType?->description ?? ''))
                ));
                $typeDesc = str_replace("\t", '    ', $typeDesc); // tabs → 4 spaces (DejaVu can't render \t)
            @endphp
            @if($typeNote || $typeDesc)
            <div style="margin-top:8px;padding-top:6px;border-top:1px solid rgba(17,24,39,.12);">
                @if($typeNote)
                <div style="font-size:9.5px;color:#374151;white-space:pre-wrap;margin-bottom:{{ $typeDesc ? '4px' : '0' }};"><strong>Notes:</strong> {{ $typeNote }}</div>
                @endif
                @if($typeDesc)
                <div style="font-size:9px;color:#6b7280;line-height:1.4;white-space:pre-wrap;font-family:'DejaVu Sans Mono',monospace;">{{ $typeDesc }}</div>
                @endif
            </div>
            @endif

        </div>

        {{-- FOOTER (PER PAGE) --}}
        @if($showFooter ?? true)
        <div class="footer-wrap">
            <div class="footer-note">{{ $setting->footer_note }}</div>
            <div class="footer-divider"></div>
            @if(!empty($setting->doctors))
                <div class="footer-doctors">
                    <table class="footer-grid">
                        <tr>
                            @foreach($setting->doctors as $doc)
                                <td>
                                    <div class="doctor-name">{{ $doc['name'] }}</div>
                                    <div class="doctor-desc">{!! nl2br(e($doc['description'] ?? '')) !!}</div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>
            @endif
        </div>
        @endif {{-- /showFooter --}}

        {{-- Bottom notice (e.g. "Not valid for Court") --}}
        @if($bottomNotice)
        <div style="
            position: absolute;
            left: 45px; right: 45px; bottom: 4px;
            font-size: {{ $bnSize }}px;
            color: {{ $bnColor }};
            text-align: {{ $bnAlign }};
            font-style: {{ $bnItalic ? 'italic' : 'normal' }};
            font-weight: {{ $bnBold ? 'bold' : 'normal' }};
        ">{{ $bottomNotice }}</div>
        @endif

    </div>
@endforeach

</body>
</html>
