{{-- resources/views/reports/order_single.blade.php --}}

@php
    use Illuminate\Support\Carbon;

    // Embed letterhead as base64 so dompdf always renders it
    $imgData = '';
    if (!empty($letterheadPath) && file_exists($letterheadPath)) {
        $type = pathinfo($letterheadPath, PATHINFO_EXTENSION);
        $data = file_get_contents($letterheadPath);
        $imgData = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    $customerUser = $order->customer->user;

    $patientName = $customerUser->name ?? '-';
    $gender = $order->customer->gender ?? '-';
    $age = '-';
    if (!empty($order->customer->dob)) {
        try { $age = Carbon::parse($order->customer->dob)->age . ' Years'; } catch (\Throwable $e) {}
    }

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
        @page { margin: 0; }

        body{
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        /* One type = one page */
        .page{
            page-break-after: always;
            position: relative;
            min-height: 100%;
            background-image: url('{{ $imgData }}');
            background-repeat: no-repeat;
            background-position: center top;
            background-size: 100% 100%;
        }
        .page:last-child{ page-break-after: auto; }

        .content{
            padding: 150px 45px 210px 45px; /* extra bottom space for footer */
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

@foreach($itemsByType as $typeId => $typeItems)
    @php
        $typeName = $typeItems->first()?->testType?->name
            ?? ($typesMap[$typeId] ?? ('Type #'.$typeId));

        // 2) Build category groups in insertion order (first-seen)
        $grouped = [];
        foreach ($typeItems as $it) {
            $category = $it->labTest?->testCategory?->name;

            // CBC special case
            if (!$category && strtoupper(trim((string)$typeName)) === 'CBC') {
                $category = 'Diff. Leuc Count (DLC)';
            }

            $category = $category ?: 'Other';

            if (!isset($grouped[$category])) $grouped[$category] = collect();
            $grouped[$category]->push($it); // keeps PURE inserted order inside category
        }
    @endphp

    <div class="page">
        <div class="content">

            {{-- Patient Meta --}}
            <table class="meta-table">
                <tr>
                    <td>
                        <div><span class="label">Patient:</span> {{ $patientName }}</div>
                        <div><span class="label">Panel:</span> {{ $labName }}</div>
                        <div><span class="label">Order No:</span> {{ str_pad((string)$order->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <div><span class="label">Ref By:</span> {{ $order->customer->ref_by ?? '-' }}</div>
                    </td>
                    <td>
                        <div><span class="label">Age / Sex:</span> {{ $age }} / {{ ucfirst($gender) }}</div>
                        <div><span class="label">Reg Date:</span> {{ $regDate }}</div>
                        <div><span class="label">Email:</span> {{ $customerUser->email ?? '-' }}</div>
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
                                $range = $it->reference_range_snapshot ?? $it->labTest?->reference_range ?? '';
                            @endphp
                            <tr>
                                <td>{{ $it->test_name_snapshot }}</td>
                                <td>
                                    @if($it->result_status === 'ready')
                                        {{ $it->result_text ?: '—' }}
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

        {{-- FOOTER (PER PAGE) --}}
        <div class="footer-wrap">
            <div class="footer-note">Electronically generated report — No need of signature</div>
            <div class="footer-divider"></div>

            <div class="footer-doctors">
                <table class="footer-grid">
                    <tr>
                        <td>
                            <div class="doctor-name">Dr Amna Shujaat Ali Naqvi</div>
                            <div class="doctor-desc">MBBS, MPhil Pathology</div>
                        </td>
                        <td>
                            <div class="doctor-name">Dr Shafqat Iqbal</div>
                            <div class="doctor-desc">MBBS, FCPS (Gastro) • BSc, CHPE<br>Consultant Gastroenterologist &amp; Hepatologist</div>
                        </td>
                        <td>
                            <div class="doctor-name">Dr Sobia Ikhlaq</div>
                            <div class="doctor-desc">MBBS, RMP • SMO Federal GH<br>Islamabad</div>
                        </td>
                        <td>
                            <div class="doctor-name">Atif Iqbal</div>
                            <div class="doctor-desc">BS &amp; MPhil Microbiology</div>
                        </td>
                        <td>
                            <div class="doctor-name">Gulfam Ali Shahzad</div>
                            <div class="doctor-desc">Lab Technologist • MSc MLS</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
@endforeach

</body>
</html>
