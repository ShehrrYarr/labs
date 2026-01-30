@extends('admin_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:20px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    .btn{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;transition:all .3s ease;font-weight:600}
    .btn:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(37,99,235,.35)}
    table{width:100%;border-collapse:collapse;margin-top:15px}
    th{background:#f1f5f9;text-align:left;padding:12px;font-size:14px;color:#334155}
    td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:14px}
    tr:hover{background:#f8fafc;transition:background .2s ease}
    .badge{padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700}
    .active{background:#dcfce7;color:#166534}
    .inactive{background:#fee2e2;color:#991b1b}
    .actions a,.actions button{margin-right:8px;font-size:13px;color:#2563eb;background:none;border:none;cursor:pointer}
    .actions a:hover,.actions button:hover{text-decoration:underline}
    .alert{background:#ecfdf5;color:#065f46;padding:12px;border-radius:8px;margin-top:12px}
</style>

<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
<div class="card">
    <div class="top">
        <div>
            <h2 style="margin:0;">Test Types</h2>
            <div style="color:#64748b;font-size:13px;margin-top:4px;">Manage dynamic test types used inside Tests.</div>
        </div>
        <a class="btn" href="{{ route('test-types.create') }}">+ Add Test Type</a>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Code</th>
                <th>Status</th>
                <th style="width:170px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($testTypes as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->price }}</td>
                    <td>{{ $type->code ?? 'N/A' }}</td>
                    <td>
                        @if($type->is_active)
                            <span class="badge active">Active</span>
                        @else
                            <span class="badge inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="actions">
                        <a href="{{ route('test-types.edit', $type) }}">Edit</a>
                        <form action="{{ route('test-types.destroy', $type) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this test type?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No test types found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">
        {{ $testTypes->links() }}
    </div>
</div>
</div>
</div>
@endsection
