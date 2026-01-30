@extends('admin_navbar')
@section('content')
<style>
    .card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        padding: 20px;
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #fff;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(37,99,235,0.35);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th {
        background: #f1f5f9;
        text-align: left;
        padding: 12px;
        font-size: 14px;
        color: #334155;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }

    tr:hover {
        background: #f8fafc;
        transition: background 0.2s ease;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-active {
        background: #dcfce7;
        color: #166534;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .actions a,
    .actions button {
        margin-right: 8px;
        font-size: 13px;
        color: #2563eb;
        background: none;
        border: none;
        cursor: pointer;
    }

    .actions button:hover,
    .actions a:hover {
        text-decoration: underline;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
</style>
 <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Branches</h2>
        <a href="{{ route('branches.create') }}" class="btn-primary">+ Create Branch</a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Branch Name</th>
                <th>Email</th>
                <th>Status</th>
                <th style="width:180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($branches as $branch)
                <tr>
                    <td>{{ $branch->id }}</td>
                    <td>{{ $branch->branch_name }}</td>
                    <td>{{ $branch->user->email }}</td>
                    <td>
                        @if($branch->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="actions">
                        <a href="{{ route('branches.edit', $branch) }}">Edit</a>
                        <form action="{{ route('branches.destroy', $branch) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this branch?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No branches found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:15px;">
        {{ $branches->links() }}
    </div>
</div>
</div>
</div>
@endsection
