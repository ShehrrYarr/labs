@extends('admin_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:20px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .top{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap}
    .pill{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;font-weight:950}
    .muted{color:#64748b;font-size:13px;margin-top:4px}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:all .25s ease;font-weight:900;font-size:14px}
    .btn-ghost{background:#f1f5f9;color:#0f172a}
    .btn-ghost:hover{transform:translateY(-1px);background:#e2e8f0}

    .search-wrap{
        margin-top:14px;
        display:flex;gap:10px;align-items:center;flex-wrap:wrap;
        background:#f8fafc;border:1px solid #e5e7eb;padding:12px;border-radius:12px;
    }
    .search-input{
        flex:1;min-width:220px;border:1px solid #e5e7eb;border-radius:10px;
        padding:10px 12px;outline:none;font-size:14px;background:#fff;
    }
    .search-input:focus{
        border-color:rgba(37,99,235,.55);
        box-shadow:0 0 0 4px rgba(37,99,235,.12);
    }
    .chip{
        display:inline-flex;gap:6px;align-items:center;background:#eef2ff;color:#3730a3;
        padding:6px 10px;border-radius:999px;font-size:12px;font-weight:900;border:1px solid #e5e7eb;
        white-space:nowrap;
    }
    .clear-btn{
        background:#fff;border:1px solid #e5e7eb;border-radius:10px;
        padding:10px 12px;cursor:pointer;font-weight:900;
    }
    .hidden{display:none!important;}
    .label{font-size:12px;color:#475569;font-weight:900}

    table{width:100%;border-collapse:collapse;margin-top:12px;background:#fff;border-radius:12px;overflow:hidden}
    th{background:#f1f5f9;text-align:left;padding:12px;font-size:13px;color:#334155}
    td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:top}
    tr:hover{background:#f8fafc}
    .badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:950;display:inline-block}
    .b-pending{background:#fef9c3;color:#854d0e}
    .b-ready{background:#dcfce7;color:#166534}
    .b-processing{background:#dbeafe;color:#1d4ed8}
    .kind{background:#f1f5f9;color:#0f172a;border:1px solid #e5e7eb}
    .small{font-size:12px;color:#64748b;margin-top:4px}
</style>

<div class="app-content content">
<div class="content-wrapper">

<div class="card">
    <div class="top">
        <div>
            <div class="pill">CUSTOMER #{{ $customer->id }}</div>
            <h2 style="margin:10px 0 0;color:#0f172a;">
                {{ $customer->user->name }} — Test History
            </h2>
            <div class="muted">
                {{ $customer->user->email }} • {{ $customer->phone ?? '-' }}
            </div>
        </div>
        <a class="btn btn-ghost" href="{{ route('customers.index') }}">Back</a>
    </div>

    {{-- Search + Date filter --}}
    <div class="search-wrap">
        <input id="testSearch" class="search-input" type="text"
               placeholder="Search test, parent test, result, unit, range...">

        <div>
            <span class="label">From</span>
            <input id="dateFrom" class="search-input" type="date">
        </div>

        <div>
            <span class="label">To</span>
            <input id="dateTo" class="search-input" type="date">
        </div>

        <span id="matchChip" class="chip hidden">0 matches</span>
        <button id="clearSearch" class="clear-btn hidden" type="button">Clear</button>
    </div>

    <table>
        <thead>
        <tr>
            <th>Test</th>
            <th>Kind</th>
            <th>Status</th>
            <th>Assigned</th>
            <th>Ready</th>
            <th>Result</th>
            <th>Unit</th>
            <th>Range</th>
        </tr>
        </thead>

        <tbody>
        @forelse($items as $it)
            @php
                $kind = $it->item_kind ?? '-'; // main / sub
                $isSub = ($kind === 'sub');

                // Name + code source:
                // - main => from snapshots
                // - sub  => snapshots + show parent test name (helpful)
                $name = $it->test_name_snapshot ?? '-';
                $code = $it->test_code_snapshot ?? '-';

                $parentName = null;
                if ($isSub) {
                    $parentName = $it->subTest?->parentTest?->test_name
                                  ?? $it->labTest?->test_name
                                  ?? null;
                }

                $unit = $it->unit_snapshot ?? '';
                $range = $it->reference_range_snapshot ?? '';
                $status = $it->result_status ?? 'pending';

                $assignedIso = optional($it->created_at)->format('Y-m-d');
                $readyIso = optional($it->result_posted_at)->format('Y-m-d');

                $qText = strtolower(
                    trim(
                        ($name ?? '') . ' ' .
                        ($code ?? '') . ' ' .
                        ($parentName ?? '') . ' ' .
                        ($it->result_text ?? '') . ' ' .
                        ($unit ?? '') . ' ' .
                        ($range ?? '')
                    )
                );

                $badgeClass = 'b-pending';
                if ($status === 'ready') $badgeClass = 'b-ready';
                elseif ($status === 'processing') $badgeClass = 'b-processing';
            @endphp

            <tr class="test-row"
                data-q="{{ $qText }}"
                data-date="{{ $assignedIso }}">

                <td>
                    <b>{{ $name }}</b>
                    <div class="small">{{ $code }}</div>

                    @if($isSub && $parentName)
                        <div class="small">Parent: <b>{{ $parentName }}</b></div>
                    @endif
                </td>

                <td>
                    <span class="badge kind">{{ strtoupper($kind) }}</span>
                </td>

                <td>
                    <span class="badge {{ $badgeClass }}">{{ strtoupper($status) }}</span>
                </td>

                <td>{{ optional($it->created_at)->format('d-m-Y') }}</td>

                <td>
                    {{ $it->result_posted_at ? $it->result_posted_at->format('d-m-Y') : '—' }}
                </td>

                <td style="white-space:pre-wrap;">
                    {{ $it->result_text ?: '—' }}
                </td>

                <td>{{ $unit ?: '—' }}</td>
                <td>{{ $range ?: '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="8">No tests found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div id="noMatch" class="muted hidden" style="margin-top:12px;">
        No matching tests found.
    </div>

</div>
</div>
</div>

<script>
(function(){
    const search = document.getElementById('testSearch');
    const from = document.getElementById('dateFrom');
    const to = document.getElementById('dateTo');
    const clearBtn = document.getElementById('clearSearch');
    const chip = document.getElementById('matchChip');
    const noMatch = document.getElementById('noMatch');
    const rows = [...document.querySelectorAll('.test-row')];

    function filter(){
        const q = (search.value || '').toLowerCase().trim();
        const f = from.value;
        const t = to.value;
        let visible = 0;

        rows.forEach(r=>{
            const textOk = !q || (r.dataset.q || '').includes(q);
            const d = r.dataset.date || '';
            const dateOk = (!f || d >= f) && (!t || d <= t);
            const show = textOk && dateOk;
            r.classList.toggle('hidden', !show);
            if(show) visible++;
        });

        const active = !!q || !!f || !!t;
        chip.textContent = visible + ' matches';
        chip.classList.toggle('hidden', !active);
        clearBtn.classList.toggle('hidden', !active);
        noMatch.classList.toggle('hidden', visible !== 0);
    }

    [search, from, to].forEach(el=>el.addEventListener('input', filter));
    clearBtn.addEventListener('click', ()=>{
        search.value=''; from.value=''; to.value='';
        filter();
    });

    filter();
})();
</script>
@endsection
