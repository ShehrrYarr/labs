@extends('admin_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:20px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .top{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:all .25s ease;font-weight:900;font-size:14px}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.35)}
    .btn-ghost{background:#f1f5f9;color:#0f172a}
    .btn-ghost:hover{transform:translateY(-1px);background:#e2e8f0}
    .muted{color:#64748b;font-size:13px}
    .pill{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;font-weight:950}
    .alert{background:#ecfdf5;color:#065f46;padding:12px;border-radius:10px;margin-top:12px}

    .grid{display:grid;grid-template-columns:1fr;gap:14px;margin-top:16px}
    .box{border:1px solid #e5e7eb;border-radius:12px;padding:14px;background:#fafafa;position:relative;overflow:hidden}
    .box::before{content:"";position:absolute;inset:0;background:radial-gradient(650px 180px at 10% 0%, rgba(37,99,235,.08), transparent 60%);pointer-events:none}
    .box h4{margin:0 0 10px;color:#0f172a;position:relative}

    label{display:block;font-size:12px;color:#475569;margin-bottom:6px;font-weight:900}
    input, textarea, select{
        width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font-size:14px;outline:none;background:#fff;transition:all .2s ease
    }
    textarea{min-height:92px;resize:vertical}
    input:focus, textarea:focus, select:focus{
        border-color:rgba(37,99,235,.6);
        box-shadow:0 0 0 4px rgba(37,99,235,.12);
        transform:translateY(-1px)
    }
    .row2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    @media(max-width:900px){.row2{grid-template-columns:1fr}}

    table{width:100%;border-collapse:collapse;margin-top:10px;background:#fff;border-radius:12px;overflow:hidden}
    th{background:#f1f5f9;text-align:left;padding:12px;font-size:13px;color:#334155}
    td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:top}
    .badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:950}
    .b-unpaid{background:#fee2e2;color:#991b1b}
    .b-partial{background:#fef9c3;color:#854d0e}
    .b-paid{background:#dcfce7;color:#166534}
    .small{font-size:12px;color:#64748b;margin-top:4px}
    .divider{height:1px;background:#e5e7eb;margin:12px 0}

    .order-row{
        display:flex;justify-content:space-between;align-items:center;gap:12px;
        padding:12px 14px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;
        cursor:pointer;transition:all .2s ease;
    }
    .order-row:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(0,0,0,.06)}
    .order-left{display:flex;flex-direction:column;gap:4px;min-width:0}
    .order-title{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
    .chev{font-weight:900;font-size:14px;width:18px;display:inline-block}
    .order-tests{color:#334155;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:720px}
    @media(max-width:900px){.order-tests{max-width:360px}}
    .order-right{display:flex;align-items:flex-start;gap:10px;flex-wrap:wrap;justify-content:flex-end}
    .order-meta{color:#64748b;font-size:12px;line-height:1.35;text-align:right}

    .order-details{display:none;margin-top:12px}
    .order-details.open{display:block;animation:fadeIn .25s ease}
    .mini-btn{padding:8px 12px;border-radius:10px}

    /* parameter table inside */
    .param-table th, .param-table td{padding:10px}
    .param-pill{display:inline-flex;gap:6px;align-items:center;border:1px solid #e5e7eb;background:#f8fafc;border-radius:999px;padding:4px 10px;font-size:12px;font-weight:900;color:#0f172a}

    /* type search */
    .typeSearchWrap{position:relative}
    .typeDropdown{
        position:absolute;z-index:20;left:0;right:0;top:100%;
        background:#fff;border:1px solid #e5e7eb;border-radius:12px;
        margin-top:6px;max-height:220px;overflow:auto;display:none;
        box-shadow:0 12px 26px rgba(0,0,0,.10)
    }
    .typeDropdown.open{display:block}
    .typeOpt{
        padding:10px 12px;cursor:pointer;border-bottom:1px solid #f1f5f9;
        display:flex;justify-content:space-between;gap:10px;align-items:center
    }
    .typeOpt:hover{background:#f8fafc}
    .typeOpt:last-child{border-bottom:0}
    .typeName{font-weight:900;color:#0f172a}
    .typePrice{font-size:12px;color:#64748b;font-weight:900}
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>

        <div class="card">
            <div class="top">
                <div>
                    <div class="pill">CUSTOMER #{{ $customer->id }}</div>
                    <h2 style="margin:10px 0 0;color:#0f172a;">{{ $customer->user->name }} — Test History</h2>
                    <div class="muted" style="margin-top:4px;">
                        Email: {{ $customer->user->email }} • Phone: {{ $customer->phone ?? '-' }}
                    </div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a class="btn btn-ghost" href="{{ route('customers.index') }}">Back</a>

                    <a class="btn btn-ghost"
                       href="{{ route('customers.report.all', ['customer' => $customer->id]) }}"
                       target="_blank">
                        Download All Orders PDF
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            <div class="grid">
                <div class="box">
                    <h4>Create New Visit / Order</h4>
                    <form method="POST" action="{{ route('customers.orders.store', ['customer' => $customer->id]) }}">
                        @csrf

                        <div class="row2">
                            <div>
                                <label>Visit Date/Time (optional)</label>
                                <input type="datetime-local" name="visited_at">
                            </div>
                            <div>
                                <label>Notes (optional)</label>
                                <input type="text" name="notes" placeholder="Any notes for this visit">
                            </div>
                        </div>

                        <div style="margin-top:12px;">
                            <button class="btn btn-primary" type="submit">Create Order</button>
                        </div>
                    </form>
                </div>

                @forelse($orders as $order)
                    @php
                        $inv = $order->invoice;
                        $status = $inv?->status ?? 'unpaid';
                        $badge = $status === 'paid' ? 'b-paid' : ($status === 'partial' ? 'b-partial' : 'b-unpaid');

                        $itemsText = $order->items->whereIn('item_kind', ['test','subtest'])->pluck('test_name_snapshot')->filter()->take(6)->implode(', ');
                        $moreCount = max(0, $order->items->whereIn('item_kind', ['test','subtest'])->count() - 6);
                        if ($moreCount > 0) $itemsText .= " +{$moreCount} more";

                        $detailsId = 'order-details-'.$order->id;

                        $invSubtotal = (float)($inv?->subtotal ?? 0);
                        $invDiscount = (float)($inv?->discount_amount ?? 0);
                        $invTotal    = (float)($inv?->total_amount ?? 0);
                        $invPaid     = (float)($inv?->paid_amount ?? 0);
                        $invRemain   = max(0, $invTotal - $invPaid);
                    @endphp

                    <div class="box" style="background:#fafafa;">
                        <div class="order-row" data-target="{{ $detailsId }}">
                            <div class="order-left">
                                <div class="order-title">
                                    <span class="chev" id="chev-{{ $order->id }}">▸</span>
                                    <span style="font-weight:950;color:#0f172a;">Order #{{ $order->id }}</span>
                                    <span class="badge {{ $badge }}">{{ strtoupper($status) }}</span>
                                </div>

                                <div class="order-tests" title="{{ $order->items->whereIn('item_kind',['test','subtest'])->pluck('test_name_snapshot')->implode(', ') }}">
                                    <b>Assigned:</b> {{ $itemsText ?: 'No tests assigned yet' }}
                                </div>

                                <div class="small">
                                    Status: <b>{{ $order->status }}</b>
                                    • Branch: <b>{{ $order->branch?->branch_name ?? 'Main/Admin' }}</b>
                                    • Created: {{ $order->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>

                            <div class="order-right">
                                {{-- ✅ Updated summary to show Subtotal/Discount/Total --}}
                                <div class="order-meta">
                                    Subtotal: <b>{{ number_format($invSubtotal, 2) }}</b><br>
                                    Discount: <b>-{{ number_format($invDiscount, 2) }}</b><br>
                                    Total: <b>{{ number_format($invTotal, 2) }}</b><br>
                                    Paid: <b>{{ number_format($invPaid, 2) }}</b><br>
                                    Remaining: <b>{{ number_format($invRemain, 2) }}</b>
                                </div>

                                <div onclick="event.stopPropagation();" style="display:flex;gap:8px;flex-wrap:wrap;">
                                    <a class="btn btn-ghost mini-btn"
                                       href="{{ route('orders.report.single', ['order' => $order->id]) }}"
                                       target="_blank">PDF</a>

                                    <a class="btn btn-ghost mini-btn"
                                       href="{{ route('orders.slip', ['order' => $order->id]) }}"
                                       target="_blank">Slip</a>

                                    <a class="btn btn-ghost mini-btn"
                                       href="{{ route('orders.receipt', ['order' => $order->id]) }}"
                                       target="_blank">Receipt</a>
                                </div>
                            </div>
                        </div>

                        <div class="order-details" id="{{ $detailsId }}">
                            <div class="divider"></div>

                            {{-- ✅ Assign by Test Type (searchable) --}}
                            <h4 style="margin:0 0 8px;">Assign by Test Type</h4>

                            <form method="POST"
                                  action="{{ route('customers.orders.items.store', ['customer' => $customer->id, 'order' => $order->id]) }}">
                                @csrf

                                <div class="row2">
                                    <div class="typeSearchWrap">
                                        <label>Search Test Type</label>
                                        <input type="text" class="typeSearchInput" placeholder="Search test type (e.g. CBC, PCR...)" autocomplete="off">
                                        <div class="typeDropdown"></div>
                                        <div class="small">Click a type from the list.</div>
                                    </div>

                                    <div>
                                        <label>Selected Test Type</label>
                                        <input type="hidden" name="test_type_id" class="typeIdInput" required>
                                        <input type="text" class="typeSelectedName" placeholder="No type selected" readonly>
                                        <div class="small typePriceHint">Price is charged at type level.</div>
                                    </div>
                                </div>

                                <div style="margin-top:12px;">
                                    <label>Preview: Tests & Subtests that will be added</label>
                                    <div class="small" style="margin-bottom:8px;">These will be inserted as individual rows. Charge will be based on the selected test type.</div>

                                    <table id="previewTable-{{ $order->id }}">
                                        <thead>
                                        <tr>
                                            <th style="width:14%;">Kind</th>
                                            <th>Name</th>
                                            <th style="width:18%;">Unit</th>
                                            <th style="width:28%;">Reference Range</th>
                                        </tr>
                                        </thead>
                                        <tbody class="previewBody">
                                            <tr><td colspan="4" class="muted">Select a test type to preview items.</td></tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div style="margin-top:10px;">
                                    <button class="btn btn-primary" type="submit">Add to Order</button>
                                </div>
                            </form>

                            <div class="divider"></div>

                            {{-- ✅ Assigned items (tests + subtests) --}}
                            <h4 style="margin:0 0 8px;">Assigned Items</h4>

                            <table>
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Kind</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Result</th>
                                    <th style="width:320px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $assigned = $order->items ?? collect();
                                @endphp

                                @forelse($assigned as $it)
                                    <tr>
                                        <td>
                                            <div style="font-weight:900;color:#0f172a;">{{ $it->test_name_snapshot }}</div>
                                            <div class="small">
                                                Code: {{ $it->test_code_snapshot ?? '-' }}
                                                @if(!empty($it->unit_snapshot)) • Unit: {{ $it->unit_snapshot }} @endif
                                            </div>
                                            @if(!empty($it->reference_range_snapshot))
                                                <div class="small" style="white-space:pre-wrap;">Ref: {{ $it->reference_range_snapshot }}</div>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="param-pill">{{ strtoupper($it->item_kind ?? '-') }}</span>
                                            @if(!empty($it->test_type_id))
                                                <div class="small">Type ID: {{ $it->test_type_id }}</div>
                                            @endif
                                        </td>

                                        <td>{{ number_format((float)($it->price_snapshot ?? 0), 2) }}</td>

                                        <td>
                                            <b>{{ $it->result_status ?? 'pending' }}</b>
                                            @if($it->result_posted_at)
                                                <div class="small">
                                                    Updated: {{ optional($it->result_posted_at)->format('Y-m-d H:i') }}
                                                    @if($it->resultPostedByUser)
                                                        • By: {{ $it->resultPostedByUser->name }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if(!empty($it->result_text))
                                                <div style="white-space:pre-wrap;">{{ \Illuminate\Support\Str::limit($it->result_text, 180) }}</div>
                                            @else
                                                <span class="small">No result yet.</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if(auth()->user()->category === 'admin')
                                                <form method="POST"
                                                      action="{{ route('customers.orders.items.result', ['customer' => $customer->id, 'order' => $order->id, 'item' => $it->id]) }}">
                                                    @csrf

                                                    <div style="display:grid;grid-template-columns:1fr 120px;gap:8px;">
                                                        <textarea name="result_text" placeholder="Enter result..." style="min-height:70px;">{{ $it->result_text }}</textarea>
                                                        <div>
                                                            <select name="result_status" required>
                                                                <option value="pending" {{ ($it->result_status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="processing" {{ ($it->result_status ?? '') === 'processing' ? 'selected' : '' }}>Processing</option>
                                                                <option value="ready" {{ ($it->result_status ?? '') === 'ready' ? 'selected' : '' }}>Ready</option>
                                                            </select>
                                                            <button class="btn btn-primary" type="submit" style="margin-top:8px;width:100%;">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @else
                                                <span class="small">View only</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">No items assigned yet.</td></tr>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="divider"></div>

                            {{-- ✅ Discount --}}
                            <h4 style="margin:0 0 8px;">Discount</h4>
                            <form method="POST" action="{{ route('customers.orders.discount', ['customer' => $customer->id, 'order' => $order->id]) }}">
                                @csrf
                                <div class="row2">
                                    <div>
                                        <label>Discount Type</label>
                                        <select name="discount_type" class="discountType" required>
                                            <option value="none" {{ ($inv?->discount_type ?? 'none') === 'none' ? 'selected' : '' }}>None</option>
                                            <option value="percent" {{ ($inv?->discount_type ?? '') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                            <option value="flat" {{ ($inv?->discount_type ?? '') === 'flat' ? 'selected' : '' }}>Flat Amount</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Discount Value</label>
                                        <input type="number" step="0.01" name="discount_value" value="{{ $inv?->discount_value ?? 0 }}" class="discountValue">
                                        <div class="small">Percent: 10 = 10%. Flat: 500 = PKR 500.</div>
                                    </div>
                                </div>
                                <div style="margin-top:10px;">
                                    <button class="btn btn-primary" type="submit">Apply Discount</button>
                                </div>
                            </form>

                            {{-- ✅ Invoice Summary Box (shows discount clearly) --}}
                            @php
                                $invSubtotal2 = (float)($inv?->subtotal ?? 0);
                                $invDiscount2 = (float)($inv?->discount_amount ?? 0);
                                $invTotal2    = (float)($inv?->total_amount ?? 0);
                                $invPaid2     = (float)($inv?->paid_amount ?? 0);
                                $invRemain2   = max(0, $invTotal2 - $invPaid2);
                            @endphp

                            <div style="margin-top:12px;border:1px solid #e5e7eb;border-radius:12px;padding:12px;background:#fff;">
                                <div style="font-weight:950;color:#0f172a;margin-bottom:6px;">Invoice Summary</div>
                                <div class="small">
                                    Subtotal (Type Charges): <b>{{ number_format($invSubtotal2, 2) }}</b><br>
                                    Discount Applied: <b>-{{ number_format($invDiscount2, 2) }}</b><br>
                                    Total Payable: <b>{{ number_format($invTotal2, 2) }}</b><br>
                                    Paid: <b>{{ number_format($invPaid2, 2) }}</b><br>
                                    Remaining: <b>{{ number_format($invRemain2, 2) }}</b>
                                </div>
                            </div>

                            <div class="divider"></div>

                            {{-- Payments --}}
                            <h4 style="margin:0 0 8px;">Payments</h4>
                            <form method="POST" action="{{ route('customers.orders.payments.store', ['customer' => $customer->id, 'order' => $order->id]) }}">
                                @csrf
                                <div class="row2">
                                    <div>
                                        <label>Amount</label>
                                        <input type="number" step="0.01" name="amount" required>
                                    </div>
                                    <div>
                                        <label>Method</label>
                                        <select name="method" required>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="online">Online</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div style="margin-top:10px;">
                                    <label>Notes (optional)</label>
                                    <input type="text" name="notes" placeholder="Payment notes">
                                </div>

                                <div style="margin-top:10px;">
                                    <button class="btn btn-primary" type="submit">Add Payment</button>
                                </div>
                            </form>

                            <table>
                                <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                    <th>Received By</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($inv?->payments ?? [] as $p)
                                    <tr>
                                        <td>{{ number_format($p->amount, 2) }}</td>
                                        <td>{{ $p->method }}</td>
                                        <td>{{ optional($p->paid_at)->format('Y-m-d H:i') }}</td>
                                        <td>{{ $p->receivedByUser?->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4">No payments yet.</td></tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                @empty
                    <div class="box">
                        <h4>No orders yet</h4>
                        <div class="muted">Create the first visit/order above, then assign items and manage invoices.</div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
(function () {
    // Expand/collapse order details
    function toggle(targetId, orderId) {
        const el = document.getElementById(targetId);
        const chev = document.getElementById('chev-' + orderId);
        if (!el) return;

        const isOpen = el.classList.contains('open');
        document.querySelectorAll('.order-details.open').forEach(d => d.classList.remove('open'));
        document.querySelectorAll('.chev').forEach(c => c.textContent = '▸');

        if (!isOpen) {
            el.classList.add('open');
            if (chev) chev.textContent = '▾';
        }
    }

    document.querySelectorAll('.order-row').forEach(row => {
        row.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const idPart = targetId ? targetId.replace('order-details-','') : '';
            toggle(targetId, idPart);
        });
    });

    // Auto-open by ?open_order=
    const params = new URLSearchParams(window.location.search);
    const openOrder = params.get('open_order');
    if (openOrder) {
        const targetId = 'order-details-' + openOrder;
        const row = document.querySelector('.order-row[data-target="' + targetId + '"]');
        if (row) {
            row.click();
            setTimeout(() => row.scrollIntoView({ behavior: 'smooth', block: 'start' }), 150);
        }
    }

    // ✅ Types data from controller ($typesForJs)
    const typesData = @json($typesForJs);

    function money(n){
        const x = Number(n || 0);
        return x.toFixed(2);
    }

    function escapeHtml(s){
        return String(s ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // For each order detail area: searchable type dropdown + preview table
    document.querySelectorAll('.order-details').forEach(details => {
        const searchInput = details.querySelector('.typeSearchInput');
        const dropdown = details.querySelector('.typeDropdown');
        const typeIdInput = details.querySelector('.typeIdInput');
        const typeNameInput = details.querySelector('.typeSelectedName');
        const priceHint = details.querySelector('.typePriceHint');
        const previewBody = details.querySelector('.previewBody');

        if(!searchInput || !dropdown || !typeIdInput || !typeNameInput || !previewBody) return;

        function renderDropdown(filterText){
            const q = (filterText || '').trim().toLowerCase();
            const list = typesData.filter(t => !q || String(t.name || '').toLowerCase().includes(q));

            dropdown.innerHTML = '';

            if(list.length === 0){
                const div = document.createElement('div');
                div.className = 'typeOpt';
                div.innerHTML = '<span class="typeName">No match</span><span class="typePrice"></span>';
                dropdown.appendChild(div);
                return;
            }

            list.forEach(t => {
                const div = document.createElement('div');
                div.className = 'typeOpt';
                div.dataset.id = t.id;
                div.innerHTML = `
                    <span class="typeName">${escapeHtml(t.name)}</span>
                    <span class="typePrice">PKR ${money(t.price)}</span>
                `;
                div.addEventListener('click', () => selectType(t));
                dropdown.appendChild(div);
            });
        }

        function buildPreviewRows(type){
            previewBody.innerHTML = '';

            if(!type){
                previewBody.innerHTML = `<tr><td colspan="4" class="muted">Select a test type to preview items.</td></tr>`;
                return;
            }

            const rows = [];
            (type.tests || []).forEach(test => {
                rows.push({
                    kind: 'TEST',
                    name: test.name,
                    unit: test.unit || '-',
                    ref: test.reference_range || '-',
                    sort: 0
                });

                (test.subtests || []).forEach(st => {
                    rows.push({
                        kind: 'SUBTEST',
                        name: st.name,
                        unit: st.unit || '-',
                        ref: st.reference_range || '-',
                        sort: Number(st.sort_order || 0)
                    });
                });
            });

            if(rows.length === 0){
                previewBody.innerHTML = `<tr><td colspan="4" class="muted">No tests found in this type.</td></tr>`;
                return;
            }

            // sort subtests under each test already; this extra sort just keeps subtests ordered
            rows.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="param-pill">${escapeHtml(r.kind)}</span></td>
                    <td style="font-weight:900;color:#0f172a;">${escapeHtml(r.name)}</td>
                    <td>${escapeHtml(r.unit)}</td>
                    <td style="white-space:pre-wrap;">${escapeHtml(r.ref)}</td>
                `;
                previewBody.appendChild(tr);
            });
        }

        function selectType(type){
            typeIdInput.value = type.id;
            typeNameInput.value = type.name;
            if(priceHint) priceHint.textContent = `Selected Type Price: PKR ${money(type.price)} (charge at type level)`;

            buildPreviewRows(type);

            dropdown.classList.remove('open');
        }

        searchInput.addEventListener('focus', () => {
            dropdown.classList.add('open');
            renderDropdown(searchInput.value);
        });

        searchInput.addEventListener('input', () => {
            dropdown.classList.add('open');
            renderDropdown(searchInput.value);
        });

        document.addEventListener('click', (e) => {
            if(!details.contains(e.target)) return;
            if(e.target === searchInput || dropdown.contains(e.target)) return;
            dropdown.classList.remove('open');
        });

        // initial
        renderDropdown('');
        buildPreviewRows(null);
    });

    // Discount UX: if "none" selected, force value to 0 (avoid accidental weird states)
    document.querySelectorAll('.discountType').forEach(sel => {
        const box = sel.closest('form');
        const val = box ? box.querySelector('.discountValue') : null;

        function sync(){
            if(!val) return;
            if(sel.value === 'none'){
                val.value = 0;
                val.setAttribute('readonly', 'readonly');
            }else{
                val.removeAttribute('readonly');
            }
        }

        sel.addEventListener('change', sync);
        sync();
    });
})();
</script>
@endsection
