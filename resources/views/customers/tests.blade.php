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

    /* type result groups */
    .type-result-group{border:1px solid #e5e7eb;border-radius:12px;margin-bottom:14px;overflow:hidden;}
    .type-result-header{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;background:#f8fafc;border-bottom:1px solid #e5e7eb;}
    .type-result-header .type-result-name{font-weight:950;font-size:14px;color:#0f172a;}
    .result-table{width:100%;border-collapse:collapse;}
    .result-table th{background:#f1f5f9;text-align:left;padding:9px 12px;font-size:12px;color:#475569;}
    .result-table td{padding:9px 12px;border-bottom:1px solid #f1f5f9;vertical-align:top;}
    .result-table textarea{width:100%;border:1px solid #e5e7eb;border-radius:8px;padding:6px 8px;font-size:12px;min-height:44px;resize:vertical;outline:none;background:#fff;box-sizing:border-box;}
    .result-table textarea:focus{border-color:rgba(37,99,235,.55);box-shadow:0 0 0 3px rgba(37,99,235,.10);}
    .result-actions{display:flex;gap:10px;padding:10px 14px;background:#fafafa;border-top:1px solid #e5e7eb;}
    .pill-ready{background:#dcfce7!important;color:#166534!important;border-color:#bbf7d0!important;}
    .pill-processing{background:#fef9c3!important;color:#854d0e!important;border-color:#fde68a!important;}

    /* type cards */
    .types-grid{
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
        gap:10px;
        margin-top:10px;
    }
    .type-card{
        border:1.5px solid #e5e7eb;border-radius:12px;
        padding:12px;background:#fff;cursor:pointer;
        transition:all .2s ease;position:relative;
    }
    .type-card:hover{
        border-color:#2563eb;
        box-shadow:0 6px 18px rgba(37,99,235,.15);
        transform:translateY(-2px);
    }
    .type-card-name{font-weight:950;font-size:14px;color:#0f172a;margin-bottom:4px;}
    .type-card-price{font-size:12px;font-weight:900;color:#2563eb;margin-bottom:6px;}
    .type-card-tests{font-size:11px;color:#64748b;line-height:1.4;}
    .type-card-btn{
        display:block;width:100%;margin-top:10px;
        padding:7px 0;border-radius:8px;border:0;cursor:pointer;
        background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;
        font-weight:900;font-size:13px;
        transition:all .2s ease;
    }
    .type-card-btn:hover{box-shadow:0 6px 14px rgba(37,99,235,.35);}
    .type-search-bar{
        width:100%;border:1px solid #e5e7eb;border-radius:10px;
        padding:9px 12px;font-size:14px;outline:none;background:#fff;
        transition:all .2s ease;box-sizing:border-box;
    }
    .type-search-bar:focus{border-color:rgba(37,99,235,.6);box-shadow:0 0 0 4px rgba(37,99,235,.12);}
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

                    <a class="btn btn-ghost report-layout-trigger"
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

                        // ✅ FORCE PURE INSERTION ORDER (ID ASC) FOR DISPLAY (no alphabetical, no kind priority)
                        $displayItems = ($order->items ?? collect())->sortBy('id')->values();

                        $displayTests = $displayItems->whereIn('item_kind', ['test','subtest']);
                        $itemsText = $displayTests->pluck('test_name_snapshot')->filter()->take(6)->implode(', ');
                        $moreCount = max(0, $displayTests->count() - 6);
                        if ($moreCount > 0) $itemsText .= " +{$moreCount} more";

                        $detailsId = 'order-details-'.$order->id;

                        $invSubtotal = (float)($inv?->subtotal ?? 0);
                        $invDiscount = (float)($inv?->discount_amount ?? 0);
                        $invTotal    = (float)($inv?->total_amount ?? 0);
                        $invPaid     = (float)($inv?->paid_amount ?? 0);
                        $invRemain   = max(0, $invTotal - $invPaid);

                        // Build ordered list of test types in this order for the print modal
                        // item_kind values in DB are 'main' and 'sub'
                        $_tmap = collect($typesForJs)->keyBy('id');
                        $orderTypesForModal = $displayItems
                            ->whereIn('item_kind', ['main', 'sub'])
                            ->pluck('test_type_id')->filter()->unique()
                            ->map(fn($tid) => ['id' => (int)$tid, 'name' => $_tmap[$tid]['name'] ?? ('Type #'.$tid)])
                            ->values()->toArray();
                    @endphp

                    <div class="box" style="background:#fafafa;">
                        <div class="order-row" data-target="{{ $detailsId }}">
                            <div class="order-left">
                                <div class="order-title">
                                    <span class="chev" id="chev-{{ $order->id }}">▸</span>
                                    <span style="font-weight:950;color:#0f172a;">Order #{{ $order->id }}</span>
                                    <span class="badge {{ $badge }}">{{ strtoupper($status) }}</span>
                                </div>

                                <div class="order-tests"
                                     title="{{ $displayTests->pluck('test_name_snapshot')->implode(', ') }}">
                                    <b>Assigned:</b> {{ $itemsText ?: 'No tests assigned yet' }}
                                </div>

                                <div class="small">
                                    Status: <b>{{ $order->status }}</b>
                                    • Branch: <b>{{ $order->branch?->branch_name ?? 'Main/Admin' }}</b>
                                    • Created: {{ $order->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>

                            <div class="order-right">
                                <div class="order-meta">
                                    Subtotal: <b>{{ number_format($invSubtotal, 2) }}</b><br>
                                    Discount: <b>-{{ number_format($invDiscount, 2) }}</b><br>
                                    Total: <b>{{ number_format($invTotal, 2) }}</b><br>
                                    Paid: <b>{{ number_format($invPaid, 2) }}</b><br>
                                    Remaining: <b>{{ number_format($invRemain, 2) }}</b>
                                </div>

                                <div onclick="event.stopPropagation();" style="display:flex;gap:8px;flex-wrap:wrap;">
                                    <a class="btn btn-ghost mini-btn report-layout-trigger"
                                       href="{{ route('orders.report.single', ['order' => $order->id]) }}"
                                       data-types="{{ json_encode($orderTypesForModal) }}"
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

                            <h4 style="margin:0 0 8px;">Assign Tests</h4>

                            <input type="text"
                                   class="type-search-bar typeFilterInput"
                                   placeholder="Search test type to add (e.g. CBC, Urine R/E)..."
                                   autocomplete="off">

                            <form method="POST"
                                  class="typeForm"
                                  action="{{ route('customers.orders.items.store', ['customer' => $customer->id, 'order' => $order->id]) }}">
                                @csrf
                                <input type="hidden" name="test_type_id" class="typeIdInput" required>

                                <div class="types-grid typeCardsGrid" style="margin-top:10px;"></div>
                            </form>

                            <div class="divider"></div>

                            <h4 style="margin:0 0 8px;">Assigned Tests</h4>

                            @php
                                $typeNameMap   = collect($typesForJs)->keyBy('id');
                                $groupedItems  = $displayItems->groupBy('test_type_id');
                            @endphp

                            @forelse($groupedItems as $gTypeId => $gTypeItems)
                                @php
                                    $typeName     = $typeNameMap[$gTypeId]['name'] ?? ('Type #'.$gTypeId);
                                    $resultItems  = $gTypeItems->where('item_kind', '!=', 'charge')->values();
                                    $allReady     = $resultItems->count() > 0
                                                    && $resultItems->every(fn($i) => $i->result_status === 'ready');
                                @endphp

                                <div class="type-result-group">
                                    <div class="type-result-header">
                                        <span class="type-result-name">{{ $typeName }}</span>
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            @if($allReady)
                                                <span class="badge b-paid">ALL READY</span>
                                            @endif
                                            @if(auth()->user()->category === 'admin')
                                                <form method="POST"
                                                      action="{{ route('customers.orders.type.destroy', ['customer' => $customer->id, 'order' => $order->id, 'typeId' => $gTypeId]) }}"
                                                      onsubmit="return confirm('Remove {{ addslashes($typeName) }} from this order?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            style="padding:3px 10px;border-radius:6px;border:1px solid #fca5a5;background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;cursor:pointer;">
                                                        ✕ Remove
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    @if(auth()->user()->category === 'admin')
                                    @php
                                        $existingResultFiles = [];
                                        foreach($resultItems as $_rf) {
                                            if(!empty($_rf->result_files)) {
                                                $existingResultFiles = is_array($_rf->result_files) ? $_rf->result_files : json_decode($_rf->result_files, true) ?? [];
                                                break;
                                            }
                                        }
                                    @endphp
                                    <form method="POST" enctype="multipart/form-data"
                                          action="{{ route('customers.orders.type.result', ['customer' => $customer->id, 'order' => $order->id]) }}">
                                        @csrf
                                        <input type="hidden" name="test_type_id" value="{{ $gTypeId }}">
                                        <input type="hidden" name="mark_ready" class="markReadyInput" value="0">

                                        <table class="result-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:35%;">Test Parameter</th>
                                                    <th style="width:50%;">Result</th>
                                                    <th style="width:15%;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($resultItems as $idx => $it)
                                                <input type="hidden" name="items[{{ $idx }}][id]" value="{{ $it->id }}">
                                                <tr>
                                                    <td>
                                                        <div style="font-weight:900;color:#0f172a;">{{ $it->test_name_snapshot }}</div>
                                                        @if($it->unit_snapshot)
                                                            <div class="small">Unit: {{ $it->unit_snapshot }}</div>
                                                        @endif
                                                        @if($it->reference_range_snapshot)
                                                            <div class="small" style="white-space:pre-wrap;">Ref: {{ \Illuminate\Support\Str::limit($it->reference_range_snapshot, 80) }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <textarea name="items[{{ $idx }}][result_text]"
                                                                  placeholder="Result value...">{{ $it->result_text }}</textarea>
                                                    </td>
                                                    <td>
                                                        @php $rs = $it->result_status ?? 'pending'; @endphp
                                                        <span class="param-pill {{ $rs === 'ready' ? 'pill-ready' : ($rs === 'processing' ? 'pill-processing' : '') }}">
                                                            {{ strtoupper($rs) }}
                                                        </span>
                                                        @if($it->result_posted_at)
                                                            <div class="small">{{ optional($it->result_posted_at)->format('d-m-Y H:i') }}</div>
                                                            @if($it->resultPostedByUser)
                                                                <div class="small">By: {{ $it->resultPostedByUser->name }}</div>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        {{-- Single notes field for the entire test --}}
                                        @php $existingTypeNote = ''; foreach($resultItems as $_n) { if(!empty($_n->result_notes)){ $existingTypeNote = $_n->result_notes; break; } } @endphp
                                        <div style="padding:8px 10px;border-top:1px solid #e5e7eb;background:#fafafa;">
                                            <label style="font-size:11px;font-weight:700;color:#475569;margin-bottom:4px;display:block;">Notes for {{ $typeName }} (optional)</label>
                                            <textarea name="type_notes" rows="2"
                                                      style="width:100%;border:1px solid #e5e7eb;border-radius:6px;padding:6px 8px;font-size:12px;resize:vertical;box-sizing:border-box;"
                                                      placeholder="Add any clinical notes for this test...">{{ $existingTypeNote }}</textarea>
                                        </div>

                                        {{-- Result images upload (multiple) --}}
                                        <div style="padding:8px 10px;border-top:1px solid #e5e7eb;background:#f8fafc;">
                                            <label style="font-size:11px;font-weight:700;color:#475569;margin-bottom:6px;display:block;">Result Images (optional — shown in PDF report, 2 per row)</label>
                                            @if(!empty($existingResultFiles))
                                                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px;">
                                                    @foreach($existingResultFiles as $_ef)
                                                        @if(file_exists(public_path('result_files/' . $_ef)))
                                                            <img src="{{ asset('result_files/' . $_ef) }}"
                                                                 style="height:80px;width:auto;border:1px solid #e5e7eb;border-radius:6px;object-fit:contain;">
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div style="font-size:11px;color:#64748b;margin-bottom:6px;">Existing images shown above — upload new ones to replace all.</div>
                                            @endif
                                            <input type="file" name="result_images[]" accept="image/*" multiple
                                                   style="font-size:12px;border:1px solid #e5e7eb;border-radius:6px;padding:5px 8px;background:#fff;width:100%;box-sizing:border-box;">
                                        </div>

                                        <div class="result-actions">
                                            <button type="submit" class="btn btn-ghost"
                                                    onclick="this.form.querySelector('.markReadyInput').value='0';">
                                                Save (Processing)
                                            </button>
                                            <button type="submit" class="btn btn-primary"
                                                    onclick="this.form.querySelector('.markReadyInput').value='1';">
                                                Mark {{ $typeName }} Ready ✓
                                            </button>
                                        </div>
                                    </form>

                                    @else
                                        {{-- Branch: view only --}}
                                        <table class="result-table">
                                            <thead>
                                                <tr>
                                                    <th>Test Parameter</th>
                                                    <th>Result</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($resultItems as $it)
                                                <tr>
                                                    <td>
                                                        <div style="font-weight:900;">{{ $it->test_name_snapshot }}</div>
                                                        @if($it->unit_snapshot)<div class="small">Unit: {{ $it->unit_snapshot }}</div>@endif
                                                    </td>
                                                    <td>
                                                        @if($it->result_text)
                                                            <div style="white-space:pre-wrap;">{{ $it->result_text }}</div>
                                                        @else
                                                            <span class="small">No result yet.</span>
                                                        @endif
                                                        @if($it->result_notes)
                                                            <div class="small" style="color:#374151;"><b>Notes:</b> {{ $it->result_notes }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php $rs = $it->result_status ?? 'pending'; @endphp
                                                        <span class="param-pill {{ $rs === 'ready' ? 'pill-ready' : ($rs === 'processing' ? 'pill-processing' : '') }}">
                                                            {{ strtoupper($rs) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            @empty
                                <div class="muted">No tests assigned yet.</div>
                            @endforelse

                            <div class="divider"></div>

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

                            @if(auth()->user()->category === 'admin')
                            <div class="divider"></div>
                            <form method="POST"
                                  action="{{ route('customers.orders.destroy', ['customer' => $customer->id, 'order' => $order->id]) }}"
                                  onsubmit="return confirm('Delete Order #{{ $order->id }} permanently? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="padding:9px 18px;border-radius:10px;border:1px solid #fca5a5;background:#fee2e2;color:#991b1b;font-size:13px;font-weight:900;cursor:pointer;">
                                    🗑 Delete This Order
                                </button>
                            </form>
                            @endif

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

    const typesData = @json($typesForJs);

    function money(n){ return Number(n || 0).toFixed(2); }

    function escapeHtml(s){
        return String(s ?? '')
            .replaceAll('&','&amp;').replaceAll('<','&lt;')
            .replaceAll('>','&gt;').replaceAll('"','&quot;')
            .replaceAll("'",'&#039;');
    }

    function renderTypeCards(grid, filterInput, typeIdInput, form){
        const q = (filterInput.value || '').trim().toLowerCase();

        if(!q){
            grid.innerHTML = '<div class="small" style="color:#94a3b8;padding:6px 2px;">🔍 Type a test name above to search and add it to the order.</div>';
            return;
        }

        const list = typesData.filter(t => t.name.toLowerCase().includes(q));

        grid.innerHTML = '';

        if(list.length === 0){
            grid.innerHTML = '<div class="small" style="color:#64748b;">No matching test types found.</div>';
            return;
        }

        list.forEach(t => {
            const testsText = (t.tests && t.tests.length)
                ? t.tests.join(', ')
                : 'No tests listed';

            const card = document.createElement('div');
            card.className = 'type-card';
            card.innerHTML = `
                <div class="type-card-name">${escapeHtml(t.name)}</div>
                <div class="type-card-price">PKR ${money(t.price)}</div>
                <div class="type-card-tests">${escapeHtml(testsText)}</div>
                <button class="type-card-btn" type="button">+ Add to Order</button>
            `;

            card.querySelector('.type-card-btn').addEventListener('click', () => {
                typeIdInput.value = t.id;
                form.submit();
            });

            grid.appendChild(card);
        });
    }

    document.querySelectorAll('.order-details').forEach(details => {
        const filterInput = details.querySelector('.typeFilterInput');
        const grid        = details.querySelector('.typeCardsGrid');
        const typeIdInput = details.querySelector('.typeIdInput');
        const form        = details.querySelector('.typeForm');

        if(!filterInput || !grid || !typeIdInput || !form) return;

        renderTypeCards(grid, filterInput, typeIdInput, form);

        filterInput.addEventListener('input', () => {
            renderTypeCards(grid, filterInput, typeIdInput, form);
        });
    });

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

{{-- ══════════════════════════════════════════
     PRINT OPTIONS MODAL
══════════════════════════════════════════ --}}
<div id="reportLayoutModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div id="reportModalInner" style="background:#fff;border-radius:18px;padding:24px 28px;width:90%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,.25);">

        {{-- Title --}}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="margin:0;font-size:17px;color:#0f172a;">🖨️ Print Options</h3>
            <button onclick="closeLayoutModal()" style="border:0;background:none;font-size:22px;cursor:pointer;color:#94a3b8;line-height:1;">✕</button>
        </div>

        {{-- Two-column layout: tests left, header/footer right --}}
        <div style="display:flex;gap:20px;align-items:flex-start;">

            {{-- ── LEFT: Test selection (hidden when only 1 test type) ── --}}
            <div id="testSelectionSection" style="display:none;flex:1;min-width:0;">
                <div style="font-size:12px;font-weight:900;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Tests</div>
                <div id="testCheckboxList"
                     style="display:flex;flex-direction:column;gap:6px;max-height:210px;overflow-y:auto;
                            border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;background:#f8fafc;">
                </div>
                <div style="margin-top:7px;display:flex;gap:6px;">
                    <button type="button" onclick="selectAllTests(true)"
                            style="flex:1;padding:5px 0;border-radius:7px;border:1px solid #e2e8f0;background:#fff;font-size:11px;font-weight:700;cursor:pointer;color:#2563eb;">
                        All
                    </button>
                    <button type="button" onclick="selectAllTests(false)"
                            style="flex:1;padding:5px 0;border-radius:7px;border:1px solid #e2e8f0;background:#fff;font-size:11px;font-weight:700;cursor:pointer;color:#64748b;">
                        None
                    </button>
                </div>
            </div>

            {{-- ── RIGHT: Font size + Header / Footer layout ── --}}
            <div style="flex:1;min-width:0;">
                <div style="font-size:12px;font-weight:900;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Font Size</div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                    <input type="number" id="reportFontSize" value="11" min="7" max="18"
                           style="width:70px;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:700;text-align:center;outline:none;">
                    <span style="font-size:13px;color:#64748b;">px &nbsp;(7 – 18)</span>
                </div>
                <div style="font-size:12px;font-weight:900;color:#475569;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">Layout</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">

                    <button class="layout-opt-btn" onclick="openReport('full')"
                            style="border:2px solid #e2e8f0;border-radius:10px;padding:10px 6px;cursor:pointer;background:#fff;text-align:center;transition:all .15s;">
                        <div style="display:flex;flex-direction:column;height:52px;border:1px solid #cbd5e1;border-radius:5px;overflow:hidden;margin-bottom:6px;background:#f8fafc;">
                            <div style="height:22%;background:#94a3b8;"></div>
                            <div style="flex:1;background:repeating-linear-gradient(180deg,#e2e8f0 0,#e2e8f0 2px,transparent 2px,transparent 6px);margin:3px 4px;"></div>
                            <div style="height:18%;background:#94a3b8;"></div>
                        </div>
                        <div style="font-weight:900;font-size:11px;color:#0f172a;">Header + Footer</div>
                    </button>

                    <button class="layout-opt-btn" onclick="openReport('header')"
                            style="border:2px solid #e2e8f0;border-radius:10px;padding:10px 6px;cursor:pointer;background:#fff;text-align:center;transition:all .15s;">
                        <div style="display:flex;flex-direction:column;height:52px;border:1px solid #cbd5e1;border-radius:5px;overflow:hidden;margin-bottom:6px;background:#f8fafc;">
                            <div style="height:22%;background:#94a3b8;"></div>
                            <div style="flex:1;background:repeating-linear-gradient(180deg,#e2e8f0 0,#e2e8f0 2px,transparent 2px,transparent 6px);margin:3px 4px;"></div>
                            <div style="height:18%;background:transparent;"></div>
                        </div>
                        <div style="font-weight:900;font-size:11px;color:#0f172a;">Header Only</div>
                    </button>

                    <button class="layout-opt-btn" onclick="openReport('footer')"
                            style="border:2px solid #e2e8f0;border-radius:10px;padding:10px 6px;cursor:pointer;background:#fff;text-align:center;transition:all .15s;">
                        <div style="display:flex;flex-direction:column;height:52px;border:1px solid #cbd5e1;border-radius:5px;overflow:hidden;margin-bottom:6px;background:#f8fafc;">
                            <div style="height:22%;background:transparent;"></div>
                            <div style="flex:1;background:repeating-linear-gradient(180deg,#e2e8f0 0,#e2e8f0 2px,transparent 2px,transparent 6px);margin:3px 4px;"></div>
                            <div style="height:18%;background:#94a3b8;"></div>
                        </div>
                        <div style="font-weight:900;font-size:11px;color:#0f172a;">Footer Only</div>
                    </button>

                    <button class="layout-opt-btn" onclick="openReport('none')"
                            style="border:2px solid #e2e8f0;border-radius:10px;padding:10px 6px;cursor:pointer;background:#fff;text-align:center;transition:all .15s;">
                        <div style="display:flex;flex-direction:column;height:52px;border:1px solid #cbd5e1;border-radius:5px;overflow:hidden;margin-bottom:6px;background:#f8fafc;">
                            <div style="height:22%;background:transparent;"></div>
                            <div style="flex:1;background:repeating-linear-gradient(180deg,#e2e8f0 0,#e2e8f0 2px,transparent 2px,transparent 6px);margin:3px 4px;"></div>
                            <div style="height:18%;background:transparent;"></div>
                        </div>
                        <div style="font-weight:900;font-size:11px;color:#0f172a;">No Header / Footer</div>
                    </button>

                </div>
            </div>
        </div>

        <div style="margin-top:16px;text-align:right;">
            <button onclick="closeLayoutModal()" style="padding:7px 22px;border-radius:8px;border:1px solid #e2e8f0;background:#f1f5f9;cursor:pointer;font-size:13px;font-weight:700;color:#475569;">Cancel</button>
        </div>
    </div>
</div>

<style>
.layout-opt-btn:hover { border-color:#2563eb !important; background:#eff6ff !important; }
.layout-opt-btn:hover div:first-child { border-color:#93c5fd !important; }
</style>

<script>
var _reportBaseUrl = '';

function _escHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

document.querySelectorAll('a.report-layout-trigger').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        _reportBaseUrl = this.href;

        /* Populate test checkboxes from data-types attribute */
        var types = [];
        try { types = JSON.parse(this.getAttribute('data-types') || '[]'); } catch(ex){}

        var section  = document.getElementById('testSelectionSection');
        var listWrap = document.getElementById('testCheckboxList');

        var inner = document.getElementById('reportModalInner');
        if (types.length > 1) {
            listWrap.innerHTML = '';
            types.forEach(function(t) {
                var lbl = document.createElement('label');
                lbl.style.cssText = 'display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#0f172a;';
                lbl.innerHTML = '<input type="checkbox" value="' + t.id + '" checked '
                    + 'style="width:16px;height:16px;cursor:pointer;accent-color:#2563eb;flex-shrink:0;"> '
                    + _escHtml(t.name);
                listWrap.appendChild(lbl);
            });
            section.style.display = 'flex';
            section.style.flexDirection = 'column';
            if (inner) inner.style.maxWidth = '680px';
        } else {
            section.style.display = 'none';
            if (inner) inner.style.maxWidth = '420px';
        }

        document.getElementById('reportLayoutModal').style.display = 'flex';
    });
});

function selectAllTests(checked) {
    document.querySelectorAll('#testCheckboxList input[type=checkbox]').forEach(function(cb){ cb.checked = checked; });
}

function openReport(layout) {
    /* Collect checked test type IDs (if selection is visible) */
    var section = document.getElementById('testSelectionSection');
    var typeParams = '';
    if (section.style.display !== 'none') {
        document.querySelectorAll('#testCheckboxList input[type=checkbox]:checked').forEach(function(cb) {
            typeParams += '&types[]=' + encodeURIComponent(cb.value);
        });
    }

    var fontSize = parseInt(document.getElementById('reportFontSize').value, 10) || 11;
    fontSize = Math.max(7, Math.min(18, fontSize));

    var sep = _reportBaseUrl.indexOf('?') === -1 ? '?' : '&';
    var url = _reportBaseUrl + sep + 'layout=' + layout + typeParams + '&font_size=' + fontSize;
    window.open(url, '_blank');
    closeLayoutModal();
}

function closeLayoutModal() {
    document.getElementById('reportLayoutModal').style.display = 'none';
}

// Close on backdrop click
document.getElementById('reportLayoutModal').addEventListener('click', function(e) {
    if (e.target === this) closeLayoutModal();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLayoutModal();
});
</script>
@endsection
