@php
    use Illuminate\Support\Carbon;

    $imgData = '';
    if (!empty($letterheadPath) && file_exists($letterheadPath)) {
        $type = pathinfo($letterheadPath, PATHINFO_EXTENSION);
        $data = file_get_contents($letterheadPath);
        $imgData = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    $patientName = $customer->user->name ?? '-';
    $gender = $customer->gender ?? '-';
    $age = '-';
    if (!empty($customer->dob)) {
        try { $age = Carbon::parse($customer->dob)->age . ' Years'; } catch (\Throwable $e) {}
    }

    // ✅ helper: MAIN first then SUB
    $kindRank = function ($kind) {
        $k = strtolower((string)$kind);
        if ($k === 'main') return 0;
        if ($k === 'sub')  return 1;
        return 2;
    };
@endphp

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Customer All Orders</title>

<style>
    @page { margin: 0; }

    body {
        margin: 0;
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 12px;
        color: #111827;
    }

    .page {
        page-break-after: always;
        position: relative;
        min-height: 100%;

        background-image: url('{{ $imgData }}');
        background-repeat: no-repeat;
        background-position: center top;
        background-size: 100% 100%;
    }
    .page:last-child { page-break-after: auto; }

    .content {
        padding: 165px 50px 190px 50px;
    }

    .footer-note {
        position: absolute;
        bottom: 28px;
        left: 50px;
        right: 50px;
        text-align: center;
        font-size: 10px;
        color: #374151;
    }

    .footer-divider {
        position: absolute;
        bottom: 56px;
        left: 50px;
        right: 50px;
        height: 1px;
        background: rgba(17,24,39,.25);
    }

    .footer-doctors {
        position: absolute;
        bottom: 70px;
        left: 50px;
        right: 50px;
        font-size: 10.5px;
    }

    .footer-grid { width: 100%; border-collapse: collapse; }
    .footer-grid td { width:25%; vertical-align: top; padding-right: 6px; }

    .doctor-name { font-weight: 800; }
    .doctor-desc { font-size: 10px; color:#374151; line-height:1.35; }

    .meta-table { width:100%; border-collapse: collapse; }
    .meta-table td { padding:3px 0; }

    .label { font-weight:700; width:110px; }
    .hr { height:1px; background:#111827; opacity:.35; margin:10px 0 14px; }

    .section-title { font-size:16px; font-weight:800; margin:0 0 8px; }

    table.report { width:100%; border-collapse: collapse; }
    table.report th { padding:8px; border-bottom:1px solid rgba(17,24,39,.35); text-align:left; }
    table.report td { padding:8px; border-bottom:1px solid rgba(17,24,39,.12); vertical-align:top; }

    .small { font-size:11px; color:#374151; }
    .cat { margin-top: 14px; }
    .muted { font-size:10.5px; color:#6b7280; margin-top:3px; }
</style>
</head>

<body>

@foreach($orders as $order)
@php
    $labName = $order->branch?->branch_name ?? $mainLabName;
    $regDate = $order->created_at
        ? Carbon::parse($order->created_at)->format('d-m-Y h:i A')
        : '-';

    $grouped = $order->items->groupBy(fn($it) =>
        $it->labTest?->testCategory?->name ?? 'Other'
    );
@endphp

<div class="page">

    <div class="content">

        <table class="meta-table">
            <tr>
                <td>
                    <div><span class="label">Patient's Name:</span> {{ $patientName }}</div>
                    <div><span class="label">Lab Name:</span> {{ $labName }}</div>
                    <div><span class="label">Order No:</span> {{ $order->id }}</div>
                    <div><span class="label">Ref By:</span> {{ $customer->ref_by ?? '-' }}</div>
                </td>
                <td>
                    <div><span class="label">Age / Sex:</span> {{ $age }} / {{ ucfirst($gender) }}</div>
                    <div><span class="label">Reg Date:</span> {{ $regDate }}</div>
                    <div><span class="label">Email:</span> {{ $customer->user->email ?? '-' }}</div>
                    <div><span class="label">Phone:</span> {{ $customer->phone ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <div class="hr"></div>

        @foreach($grouped as $categoryName => $items)
            @php
                // ✅ sort: MAIN first, then SUB; within each group sort_order_snapshot then id
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

            <div class="cat">
                <div class="section-title">{{ $categoryName }}</div>

                <table class="report">
                    <thead>
                    <tr>
                        <th style="width:38%;">Test Name</th>
                        <th style="width:20%;">Result</th>
                        <th style="width:18%;">Unit</th>
                        <th style="width:24%;">Reference Range</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sortedItems as $it)
                        @php
                            $unit = $it->unit_snapshot ?? $it->labTest?->unit ?? '';
                            $range = $it->reference_range_snapshot ?? $it->labTest?->reference_range ?? '';

                            // Optional: show parent name for SUB rows (nice for understanding)
                            $parentName = null;
                            if (strtolower((string)$it->item_kind) === 'sub') {
                                $parentName = $it->subTest?->parentTest?->test_name ?? null;
                            }
                        @endphp

                        <tr>
                            <td>
                                {{ $it->test_name_snapshot }}
                                @if($parentName)
                                    <div class="muted">Parent: {{ $parentName }}</div>
                                @endif
                            </td>
                              <td>
        @if($it->result_status === 'ready')
            {{ $it->result_text ?: '—' }}
        @else
            <span style="color:#9ca3af;font-style:italic;">Processing</span>
        @endif
    </td>
                            <td>{{ $unit }}</td>
                            <td>{{ $range }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="small">Order Status: <b>{{ $order->status }}</b></div>
            </div>
        @endforeach

    </div>

    {{-- FOOTERS PER PAGE --}}
    <div class="footer-doctors">
        <table class="footer-grid">
            <tr>
                <td>
                    <div class="doctor-name">Dr Amna Shujaat Ali Naqvi</div>
                    <div class="doctor-desc">MBBS, MPhil Pathology</div>
                </td>
                <td>
                    <div class="doctor-name">Dr Shafqat Iqbal</div>
                    <div class="doctor-desc">
                        MBBS, FCPS (Gastro)<br>
                        BSc, CHPE<br>
                        Consultant Gastroenterologist & Hepatologist
                    </div>
                </td>
                <td>
                    <div class="doctor-name">Dr Sobia Ikhlaq</div>
                    <div class="doctor-desc">
                        MBBS, RMP<br>
                        SMO Federal General Hospital<br>
                        Chak Shehzad, Islamabad
                    </div>
                </td>
                <td>
                    <div class="doctor-name">Atif Iqbal</div>
                    <div class="doctor-desc">
                        BS (Microbiology)<br>
                        MPhil Microbiology
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-note">
        Electronically generated report — No need of signature
    </div>

</div>
@endforeach

</body>
</html>
