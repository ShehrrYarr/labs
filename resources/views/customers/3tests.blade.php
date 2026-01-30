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

    /* Collapsible order rows */
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
    .order-meta{color:#64748b;font-size:12px;line-height:1.3;text-align:right}

    .order-details{display:none;margin-top:12px}
    .order-details.open{display:block;animation:fadeIn .25s ease}
    .mini-btn{padding:8px 12px;border-radius:10px}
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
                {{-- Create new order --}}
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

                {{-- Orders --}}
                @forelse($orders as $order)
                    @php
                        $inv = $order->invoice;
                        $status = $inv?->status ?? 'unpaid';
                        $badge = $status === 'paid' ? 'b-paid' : ($status === 'partial' ? 'b-partial' : 'b-unpaid');

                        $testNames = $order->items->pluck('test_name_snapshot')->filter()->take(6)->implode(', ');
                        $moreCount = max(0, $order->items->count() - 6);
                        if ($moreCount > 0) $testNames .= " +{$moreCount} more";

                        $detailsId = 'order-details-'.$order->id;
                    @endphp

                    <div class="box" style="background:#fafafa;">
                        {{-- Collapsed Row --}}
                        <div class="order-row" data-target="{{ $detailsId }}">
                            <div class="order-left">
                                <div class="order-title">
                                    <span class="chev" id="chev-{{ $order->id }}">▸</span>
                                    <span style="font-weight:950;color:#0f172a;">Order #{{ $order->id }}</span>
                                    <span class="badge {{ $badge }}">{{ strtoupper($status) }}</span>
                                </div>

                                <div class="order-tests" title="{{ $order->items->pluck('test_name_snapshot')->implode(', ') }}">
                                    <b>Tests:</b> {{ $testNames ?: 'No tests assigned yet' }}
                                </div>

                                <div class="small">
                                    Status: <b>{{ $order->status }}</b>
                                    • Branch: <b>{{ $order->branch?->branch_name ?? 'Main/Admin' }}</b>
                                    • Created: {{ $order->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>

                            <div class="order-right">
                                <div class="order-meta">
                                    Total: <b>{{ number_format($inv?->total_amount ?? 0, 2) }}</b><br>
                                    Paid: <b>{{ number_format($inv?->paid_amount ?? 0, 2) }}</b>
                                </div>

                                {{-- Stop click bubbling so row doesn't toggle when clicking button --}}
                                <div onclick="event.stopPropagation();">
                                    <a class="btn btn-ghost mini-btn"
                                       href="{{ route('orders.report.single', ['order' => $order->id]) }}"
                                       target="_blank">
                                        PDF
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Expanded Content --}}
                        <div class="order-details" id="{{ $detailsId }}">
                            <div class="divider"></div>

                            {{-- Assign tests --}}
                            <h4 style="margin:0 0 8px;">Assign Tests</h4>
                            <form method="POST" action="{{ route('customers.orders.items.store', ['customer' => $customer->id, 'order' => $order->id]) }}">
                                @csrf
                                <label>Select Tests (Ctrl/Command for multi-select)</label>
                                <select name="lab_test_ids[]" multiple size="7" required>
                                    @foreach($availableTests as $t)
                                        <option value="{{ $t->id }}">
                                            {{ $t->test_name }} ({{ $t->test_code }}) - {{ number_format($t->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>

                                <div style="margin-top:10px;">
                                    <button class="btn btn-primary" type="submit">Add to Order</button>
                                </div>
                            </form>

                            <div class="divider"></div>

                            {{-- Assigned tests + Results --}}
                            <h4 style="margin:0 0 8px;">Assigned Tests</h4>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Test</th>
                                        <th>Code</th>
                                        <th>Price</th>
                                        <th>Result Status</th>
                                        <th>Result</th>
                                        <th style="width:320px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $it)
                                        <tr>
                                            <td>{{ $it->test_name_snapshot }}</td>
                                            <td>{{ $it->test_code_snapshot }}</td>
                                            <td>{{ number_format($it->price_snapshot, 2) }}</td>

                                            <td>
                                                <b>{{ $it->result_status }}</b>
                                                @if($it->result_posted_at)
                                                    <div class="small">
                                                        Posted: {{ optional($it->result_posted_at)->format('Y-m-d H:i') }}
                                                        @if($it->resultPostedByUser)
                                                            • By: {{ $it->resultPostedByUser->name }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>

                                            <td>
                                                @if($it->result_text)
                                                    <div style="white-space:pre-wrap;">{{ \Illuminate\Support\Str::limit($it->result_text, 180) }}</div>
                                                @else
                                                    <span class="small">No result yet.</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(auth()->user()->category === 'admin')
                                                    @if(!empty($it) && !empty($it->id))
                                                        <form method="POST"
                                                            action="{{ route('customers.orders.items.result', ['customer' => $customer->id, 'order' => $order->id, 'item' => $it->id]) }}">
                                                            @csrf
                                                            <div style="display:grid;grid-template-columns:1fr 110px;gap:8px;">
                                                                <textarea name="result_text" placeholder="Enter result..." style="min-height:70px;">{{ $it->result_text }}</textarea>
                                                                <div>
                                                                    <select name="result_status" required>
                                                                        <option value="processing" {{ $it->result_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                                        <option value="ready" {{ $it->result_status === 'ready' ? 'selected' : '' }}>Ready</option>
                                                                    </select>
                                                                    <button class="btn btn-primary" type="submit" style="margin-top:8px;width:100%;">Save</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <span class="small">Item not loaded properly (missing ID).</span>
                                                    @endif
                                                @else
                                                    <span class="small">View only</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No tests assigned yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="divider"></div>

                            {{-- Discount --}}
                            <h4 style="margin:0 0 8px;">Discount</h4>
                            <form method="POST" action="{{ route('customers.orders.discount', ['customer' => $customer->id, 'order' => $order->id]) }}">
                                @csrf
                                <div class="row2">
                                    <div>
                                        <label>Discount Type</label>
                                        <select name="discount_type" required>
                                            <option value="none" {{ ($inv?->discount_type ?? 'none') === 'none' ? 'selected' : '' }}>None</option>
                                            <option value="percent" {{ ($inv?->discount_type ?? '') === 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                            <option value="flat" {{ ($inv?->discount_type ?? '') === 'flat' ? 'selected' : '' }}>Flat Amount</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label>Discount Value</label>
                                        <input type="number" step="0.01" name="discount_value" value="{{ $inv?->discount_value ?? 0 }}">
                                        <div class="small">Percent: 10 = 10%. Flat: 500 = PKR 500.</div>
                                    </div>
                                </div>
                                <div style="margin-top:10px;">
                                    <button class="btn btn-primary" type="submit">Apply Discount</button>
                                </div>
                            </form>

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
                        <div class="muted">Create the first visit/order above, then assign tests and manage invoices.</div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
   

    (function () {
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
    })();

     const params = new URLSearchParams(window.location.search);
const openOrder = params.get('open_order');
if (openOrder) {
    const targetId = 'order-details-' + openOrder;
    const row = document.querySelector('.order-row[data-target="' + targetId + '"]');
    if (row) {
        row.click();
        setTimeout(() => {
            row.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 150);
    }
}
</script>

@endsection
