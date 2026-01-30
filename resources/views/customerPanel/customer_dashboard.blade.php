@extends('customerPanel.customer_navbar')
@section('content')
<style>
    .wrap{padding:18px}
    .hero{
        background: radial-gradient(900px 240px at 10% 0%, rgba(37,99,235,.18), transparent 60%),
                    radial-gradient(900px 240px at 90% 0%, rgba(16,185,129,.16), transparent 60%),
                    linear-gradient(135deg,#0f172a,#111827);
        color:#fff;border-radius:16px;padding:18px;
        box-shadow:0 12px 30px rgba(0,0,0,.22);
        animation:fadeIn .5s ease;
        display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;align-items:center
    }
    .pill{display:inline-flex;gap:8px;align-items:center;background:rgba(255,255,255,.12);padding:8px 12px;border-radius:999px;font-weight:900}
    .hero h2{margin:10px 0 0;font-weight:950}
    .muted{color:rgba(255,255,255,.82);font-size:13px;margin-top:6px}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 14px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:.25s;font-weight:900;font-size:14px}
    .btn-ghost{background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.16)}
    .btn-ghost:hover{transform:translateY(-1px);background:rgba(255,255,255,.16)}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.35)}
    .tabs{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
    .tab{background:#f1f5f9;color:#0f172a;border:1px solid #e5e7eb}
    .tab.active{background:#eef2ff;color:#3730a3;border-color:#c7d2fe}
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:16px;margin-top:12px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .search-wrap{display:flex;gap:10px;flex-wrap:wrap;align-items:center;background:#f8fafc;border:1px solid #e5e7eb;padding:12px;border-radius:12px;margin-top:12px}
    .search-input{flex:1;min-width:260px;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font-size:14px;outline:none}
    .search-input:focus{border-color:rgba(37,99,235,.55);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
    .chip{display:inline-flex;gap:6px;align-items:center;background:#eef2ff;color:#3730a3;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:900;border:1px solid #e5e7eb}
    .hidden{display:none!important}
    table{width:100%;border-collapse:collapse;margin-top:12px;border-radius:12px;overflow:hidden}
    th{background:#f1f5f9;text-align:left;padding:12px;font-size:13px;color:#334155}
    td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:top}
    tr:hover{background:#f8fafc}

    .badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:950}
    .b-unpaid{background:#fee2e2;color:#991b1b}
    .b-partial{background:#fef9c3;color:#854d0e}
    .b-paid{background:#dcfce7;color:#166534}
    .b-pending{background:#fef9c3;color:#854d0e}
    .b-ready{background:#dcfce7;color:#166534}

    .order-row{
        display:flex;justify-content:space-between;align-items:center;gap:12px;
        padding:12px 14px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;
        cursor:pointer;transition:all .2s ease;margin-top:10px
    }
    .order-row:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(0,0,0,.06)}
    .order-left{display:flex;flex-direction:column;gap:4px;min-width:0}
    .order-tests{color:#334155;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:720px}
    .order-right{display:flex;gap:10px;align-items:center;flex-wrap:wrap;justify-content:flex-end}
    .order-details{display:none;margin-top:10px}
    .order-details.open{display:block;animation:fadeIn .25s ease}
    .chev{font-weight:950;width:18px;display:inline-block}
    .small{font-size:12px;color:#64748b}
    .money{font-variant-numeric:tabular-nums}
</style>

@php
    $allOrdersPdfUrl = route('customers.report.all', ['customer' => $customer->id]);
@endphp
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
<div class="wrap">
    <div class="hero">
        <div>
            <div class="pill">Customer Panel</div>
            <h2>{{ $customer->user->name }}</h2>
            <div class="muted">{{ $customer->user->login_id }} • {{ $customer->phone ?? '-' }}</div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a class="btn btn-ghost" href="{{ $allOrdersPdfUrl }}" target="_blank">Download All Reports (PDF)</a>
        </div>
    </div>

    <div class="tabs">
        <button type="button" class="btn tab active" data-tab="orders">Order History</button>
        <button type="button" class="btn tab" data-tab="tests">Test History</button>
    </div>

    {{-- Search --}}
    <div class="search-wrap">
        <input id="globalSearch" class="search-input" type="text"
               placeholder="Realtime search (orders/tests)... e.g., CBC, pending, mg/dL"
               autocomplete="off">
        <span id="matchChip" class="chip hidden">0 matches</span>
        <button id="clearSearch" class="btn tab hidden" type="button">Clear</button>
    </div>

    {{-- ORDERS TAB --}}
    <div id="tab-orders" class="card">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center;">
            <h3 style="margin:0;color:#0f172a;">Order History</h3>
            <div class="small">Click an order to expand and view tests</div>
        </div>

        @forelse($orders as $order)
            @php
                $inv = $order->invoice;
                $status = $inv?->status ?? 'unpaid';
                $badge = $status === 'paid' ? 'b-paid' : ($status === 'partial' ? 'b-partial' : 'b-unpaid');

                $testNames = $order->items->pluck('test_name_snapshot')->filter()->take(6)->implode(', ');
                $moreCount = max(0, $order->items->count() - 6);
                if ($moreCount > 0) $testNames .= " +{$moreCount} more";

                $detailsId = 'order-details-'.$order->id;

                $q = strtolower(
                    'order '.$order->id.' '.
                    ($order->branch?->branch_name ?? 'main').' '.
                    $status.' '.
                    ($testNames ?? '').' '.
                    ($inv?->total_amount ?? 0).' '.
                    ($inv?->paid_amount ?? 0)
                );
            @endphp

            <div class="order-row order-search" data-target="{{ $detailsId }}" data-q="{{ $q }}">
                <div class="order-left">
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                        <span class="chev" id="chev-{{ $order->id }}">▸</span>
                        <span style="font-weight:950;color:#0f172a;">Order #{{ $order->id }}</span>
                        <span class="badge {{ $badge }}">{{ strtoupper($status) }}</span>
                        <span class="small">Created: {{ $order->created_at?->format('Y-m-d H:i') }}</span>
                    </div>

                    <div class="order-tests" title="{{ $order->items->pluck('test_name_snapshot')->implode(', ') }}">
                        <b>Tests:</b> {{ $testNames ?: 'No tests assigned yet' }}
                    </div>

                    <div class="small">
                        Lab: <b>{{ $order->branch?->branch_name ?? 'Main/Admin' }}</b>
                    </div>
                </div>

                

                <div class="order-right" onclick="event.stopPropagation();">
                    <div class="small money" style="text-align:right;">
                        Total: <b>{{ number_format($inv?->total_amount ?? 0, 2) }}</b><br>
                        Paid: <b>{{ number_format($inv?->paid_amount ?? 0, 2) }}</b><br>
                        Remaining: <b>{{ number_format(max(0, ($inv?->total_amount ?? 0) - ($inv?->paid_amount ?? 0)), 2) }}</b>
                    </div>

                    <a class="btn btn-ghost"
                       href="{{ route('orders.report.single', ['order' => $order->id]) }}"
                       target="_blank"
                       style="padding:8px 12px;">
                        PDF
                    </a>
                </div>
            </div>

            <div class="order-details" id="{{ $detailsId }}">
                <table>
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Status</th>
                            <th>Result</th>
                            <th>Unit</th>
                            <th>Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $it)
                            @php
                                $unit  = $it->unit_snapshot ?? $it->labTest?->unit ?? '';
                                $range = $it->reference_range_snapshot ?? $it->labTest?->reference_range ?? '';
                                $rstatus = $it->result_status ?? 'pending';
                                $isPending = in_array($rstatus, ['pending','processing'], true);
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:950;color:#0f172a;">{{ $it->test_name_snapshot }}</div>
                                    <div class="small">{{ $it->test_code_snapshot }}</div>
                                    
                                </td>
                                <td>
                                    @if($isPending)
                                        <span class="badge b-pending">Pending</span>
                                    @else
                                        <span class="badge b-ready">Ready</span>
                                    @endif
                                </td>
                                <td style="white-space:pre-wrap;">{{ $it->result_text ?: '—' }}</td>
                                <td>{{ $unit ?: '—' }}</td>
                                <td>{{ $range ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No tests assigned yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @empty
            <div class="small" style="margin-top:10px;">No orders found.</div>
        @endforelse
    </div>

    {{-- TESTS TAB --}}
    <div id="tab-tests" class="card hidden">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center;">
            <h3 style="margin:0;color:#0f172a;">Test History</h3>
            <div class="small">All tests across all orders</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Test</th>
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
                        $unit  = $it->unit_snapshot ?? $it->labTest?->unit ?? '';
                        $range = $it->reference_range_snapshot ?? $it->labTest?->reference_range ?? '';
                        $result = $it->result_text ?? '';
                        $status = $it->result_status ?? 'pending';
                        $isPending = in_array($status, ['pending','processing'], true);

                        $q = strtolower(
                            ($it->test_name_snapshot ?? '').' '.
                            ($result ?? '').' '.
                            ($unit ?? '').' '.
                            ($range ?? '').' '.
                            optional($it->created_at)->format('Y-m-d').' '.
                            optional($it->result_posted_at)->format('Y-m-d').' '.
                            'order '.$it->test_order_id
                        );
                    @endphp

                    <tr class="test-search" data-q="{{ $q }}">
                        <td>
                            <div style="font-weight:950;color:#0f172a;">{{ $it->test_name_snapshot ?? ($it->labTest?->test_name ?? '-') }}</div>
                            <div class="small">{{ $it->test_code_snapshot ?? ($it->labTest?->test_code ?? '') }}</div>
                            <div class="small">Order #{{ $it->test_order_id }}</div>
                        </td>
                        <td>
                            @if($isPending)
                                <span class="badge b-pending">Pending</span>
                            @else
                                <span class="badge b-ready">Ready</span>
                            @endif
                        </td>
                        <td>
                            {{ $it->created_at ? $it->created_at->format('d-m-Y') : '—' }}
                            <div class="small">{{ $it->created_at ? $it->created_at->format('h:i A') : '' }}</div>
                        </td>
                        <td>
                            @if($it->result_posted_at)
                                {{ $it->result_posted_at->format('d-m-Y') }}
                                <div class="small">{{ $it->result_posted_at->format('h:i A') }}</div>
                            @else
                                <span class="small">Not ready</span>
                            @endif
                        </td>
                        <td style="white-space:pre-wrap;">{{ $result !== '' ? $result : '—' }}</td>
                        <td>{{ $unit ?: '—' }}</td>
                        <td>{{ $range ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7">No tests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</div>
</div>

<script>
(function(){
    const tabs = Array.from(document.querySelectorAll('.tab'));
    const tabOrders = document.getElementById('tab-orders');
    const tabTests  = document.getElementById('tab-tests');

    function showTab(name){
        tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
        tabOrders.classList.toggle('hidden', name !== 'orders');
        tabTests.classList.toggle('hidden', name !== 'tests');
        applySearch(); // re-apply search on tab change
    }

    tabs.forEach(btn => btn.addEventListener('click', () => showTab(btn.dataset.tab)));

    // order expand/collapse
    function closeAllOrders(){
        document.querySelectorAll('.order-details.open').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('[id^="chev-"]').forEach(c => c.textContent = '▸');
    }
    document.querySelectorAll('.order-row').forEach(row => {
        row.addEventListener('click', function(){
            const targetId = this.getAttribute('data-target');
            const orderId = (targetId || '').replace('order-details-','');
            const el = document.getElementById(targetId);
            const chev = document.getElementById('chev-' + orderId);
            if(!el) return;

            const isOpen = el.classList.contains('open');
            closeAllOrders();
            if(!isOpen){
                el.classList.add('open');
                if(chev) chev.textContent = '▾';
            }
        });
    });

    // realtime search across current tab
    const input = document.getElementById('globalSearch');
    const chip = document.getElementById('matchChip');
    const clearBtn = document.getElementById('clearSearch');

    function setHidden(el, hide){ if(el) el.classList.toggle('hidden', hide); }

    let timer=null;
    function applySearch(){
        const q = (input.value||'').trim().toLowerCase();

        const onOrders = !tabOrders.classList.contains('hidden');
        const onTests  = !tabTests.classList.contains('hidden');

        const orderRows = Array.from(document.querySelectorAll('.order-search'));
        const testRows  = Array.from(document.querySelectorAll('.test-search'));

        if(!q){
            orderRows.forEach(r => r.classList.remove('hidden'));
            testRows.forEach(r => r.classList.remove('hidden'));
            setHidden(chip, true);
            setHidden(clearBtn, true);
            return;
        }

        setHidden(clearBtn, false);

        let visible = 0;
        if(onOrders){
            orderRows.forEach(r => {
                const show = (r.dataset.q || '').includes(q);
                r.classList.toggle('hidden', !show);
                // also hide the expanded content if parent hidden
                const targetId = r.getAttribute('data-target');
                const details = targetId ? document.getElementById(targetId) : null;
                if(details) details.classList.remove('open');
                if(show) visible++;
            });
        } else {
            orderRows.forEach(r => r.classList.remove('hidden'));
        }

        if(onTests){
            testRows.forEach(r => {
                const show = (r.dataset.q || '').includes(q);
                r.classList.toggle('hidden', !show);
                if(show) visible++;
            });
        } else {
            testRows.forEach(r => r.classList.remove('hidden'));
        }

        chip.textContent = `${visible} match${visible===1?'':'es'}`;
        setHidden(chip, false);
    }

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(applySearch, 200);
    });

    clearBtn.addEventListener('click', () => {
        input.value='';
        input.focus();
        applySearch();
    });

    // default tab
    showTab('orders');
})();
</script>
@endsection
