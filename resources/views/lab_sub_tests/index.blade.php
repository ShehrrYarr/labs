@extends('admin_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:20px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:all .25s ease;font-weight:900;font-size:14px}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.35)}
    .btn-ghost{background:#f1f5f9;color:#0f172a}
    .btn-ghost:hover{transform:translateY(-1px);background:#e2e8f0}
    table{width:100%;border-collapse:collapse;margin-top:14px;background:#fff;border-radius:12px;overflow:hidden}
    th{background:#f1f5f9;text-align:left;padding:12px;font-size:13px;color:#334155}
    td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:middle}
    tr:hover{background:#f8fafc}
    .badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:950}
    .active{background:#dcfce7;color:#166534}
    .inactive{background:#fee2e2;color:#991b1b}
    .muted{color:#64748b;font-size:13px;margin-top:4px}
    .actions a,.actions button{margin-right:10px;font-size:13px;color:#2563eb;background:none;border:none;cursor:pointer;font-weight:900}
    .actions a:hover,.actions button:hover{text-decoration:underline}
    .alert{background:#ecfdf5;color:#065f46;padding:12px;border-radius:10px;margin-top:12px}
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>

        <div class="card">
            <div class="top">
                <div>
                    <h2 style="margin:0;color:#0f172a;">Sub Tests (Under: {{ $labTest->test_name }})</h2>
                    <div class="muted">Parent Code: {{ $labTest->test_code }}</div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a class="btn btn-ghost" href="{{ route('lab-tests.index') }}">Back</a>
                    <a class="btn btn-primary" href="{{ route('lab-tests.sub-tests.create', $labTest) }}">+ Add Sub Test</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            <table>
                <thead>
                <tr>
                    <th style="width:70px;">Sort</th>
                    <th>Sub Test</th>
                    <th style="width:140px;">Code</th>
                    <th style="width:140px;">Type</th>
                    <th style="width:170px;">Category</th>
                    <th style="width:170px;">Equipment</th>
                    <th style="width:140px;">Status</th>
                    <th style="width:180px;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($subTests as $st)
                    <tr>
                        <td>{{ $st->sort_order }}</td>
                        <td>
                            <div style="font-weight:900;color:#0f172a;">{{ $st->test_name }}</div>
                            <div class="muted" style="font-size:12px;">
                                Unit: {{ $st->unit ?? '-' }} • Range: {{ $st->reference_range ?? '-' }}
                            </div>
                        </td>
                        <td>{{ $st->test_code ?? '-' }}</td>
                        <td>{{ $st->testType?->name ?? '-' }}</td>
                        <td>{{ $st->testCategory?->name ?? '-' }}</td>
                        <td>{{ $st->requiredEquipment?->name ?? '-' }}</td>
                        <td>
                            @if($st->is_active)
                                <span class="badge active">Active</span>
                            @else
                                <span class="badge inactive">Inactive</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('lab-tests.sub-tests.edit', [$labTest, $st]) }}">Edit</a>

                            <form method="POST" action="{{ route('lab-tests.sub-tests.destroy', [$labTest, $st]) }}" style="display:inline;" onsubmit="return confirm('Delete this sub test?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">No sub tests found for this parent test.</td></tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
