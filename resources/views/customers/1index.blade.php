@extends('admin_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:20px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    .btn{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;transition:all .3s ease;font-weight:800}
    .btn:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(37,99,235,.35)}
    .muted{color:#64748b;font-size:13px;margin-top:4px}

    .alert{background:#ecfdf5;color:#065f46;padding:12px;border-radius:8px;margin-top:12px}

    table{width:100%;border-collapse:collapse;margin-top:15px}
    th{background:#f1f5f9;text-align:left;padding:12px;font-size:13px;color:#334155}
    td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:middle}
    tr:hover{background:#f8fafc;transition:background .2s ease}

    .badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:900}
    .active{background:#dcfce7;color:#166534}
    .inactive{background:#fee2e2;color:#991b1b}

    .actions a,.actions button{margin-right:10px;font-size:13px;color:#2563eb;background:none;border:none;cursor:pointer;font-weight:800}
    .actions a:hover,.actions button:hover{text-decoration:underline}

    .iconbtn{
        display:inline-flex;align-items:center;justify-content:center;
        width:34px;height:34px;border-radius:10px;
        background:#eef2ff;color:#3730a3;text-decoration:none;
        transition:all .25s ease;border:1px solid #e5e7eb;
        font-weight:900;
    }
    .iconbtn:hover{transform:translateY(-2px);box-shadow:0 10px 18px rgba(0,0,0,.10)}
    .small{font-size:12px;color:#64748b}
</style>
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
<div class="card">
    <div class="top">
        <div>
            <h2 style="margin:0;">Customers</h2>
            <div class="muted">
                @if(auth()->user()->category === 'admin')
                    Admin view: you can see customers and which branch/user created them.
                @else
                    Branch view: you see only your created customers.
                @endif
            </div>
        </div>
        <a class="btn" href="{{ route('customers.create') }}">+ Add Customer</a>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
        <tr>
            <th style="width:70px;">Tests</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>

            @if(auth()->user()->category === 'admin')
                <th>Added By Branch</th>
            @endif

            <th>Status</th>
            <th style="width:200px;">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($customers as $c)
            <tr>
                <td>
                    {{-- View Tests icon (we will implement the page next) --}}
                    <a class="iconbtn" href="{{ route('customers.tests', $c) }}" title="View Tests (coming next)">🧾</a>
                    <div class="small" style="margin-top:4px;">History</div>
                </td>

                <td>
                    <div style="font-weight:900;color:#0f172a;">{{ $c->user->name }}</div>
                    <div class="small">ID: {{ $c->id }}</div>
                </td>

                <td>{{ $c->user->email }}</td>
                <td>{{ $c->phone ?? '-' }}</td>

                @if(auth()->user()->category === 'admin')
                    <td>
                        {{ $c->createdByBranch?->branch_name ?? 'Admin / Unknown' }}
                    </td>
                @endif

                <td>
                    @if($c->is_active)
                        <span class="badge active">Active</span>
                    @else
                        <span class="badge inactive">Inactive</span>
                    @endif
                </td>

                <td class="actions">
                    <a href="{{ route('customers.edit', $c) }}">Edit</a>

                    <form action="{{ route('customers.destroy', $c) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this customer? This will also delete their login user.')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ auth()->user()->category === 'admin' ? 7 : 6 }}">No customers found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">
        {{ $customers->links() }}
    </div>
</div>
</div>
</div>
@endsection
