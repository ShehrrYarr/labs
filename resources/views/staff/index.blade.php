@extends('admin_navbar')
@section('content')
<style>
    .wrap{padding:18px}
    .card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 8px 24px rgba(0,0,0,.08);border:1px solid #eef2f7}
    .top{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;font-weight:900;font-size:13px;transition:all .2s}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 16px rgba(37,99,235,.3);color:#fff}
    .btn-sm{padding:5px 11px;font-size:12px;border-radius:8px}
    .btn-edit{background:#eff6ff;color:#2563eb}
    .btn-edit:hover{background:#dbeafe;color:#1e40af}
    .btn-del{background:#fef2f2;color:#dc2626}
    .btn-del:hover{background:#fee2e2;color:#991b1b}
    .badge-active{display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:900;background:#ecfdf5;color:#065f46}
    .badge-inactive{display:inline-block;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:900;background:#fef2f2;color:#991b1b}
    .perm-chip{display:inline-block;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:800;background:#eff6ff;color:#2563eb;margin:1px}
    @keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
    .wrap>*{animation:fadeIn .45s ease}
</style>

<div class="app-content content">
<div class="content-wrapper">
<div class="content-body">
<div class="wrap">

    @if(session('success'))
        <div style="background:#ecfdf5;color:#065f46;padding:12px 16px;border-radius:10px;margin-bottom:14px;font-weight:700">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="top">
            <div>
                <h4 style="margin:0;font-weight:950;color:#0f172a">Staff Members</h4>
                <p style="margin:4px 0 0;color:#64748b;font-size:13px">Manage staff accounts and their permissions</p>
            </div>
            <a href="{{ route('staff.create') }}" class="btn btn-primary">
                <i class="feather icon-user-plus"></i> Add Staff
            </a>
        </div>

        @if($staffList->isEmpty())
            <div style="text-align:center;padding:40px 0;color:#94a3b8">
                <i class="feather icon-users" style="font-size:40px;display:block;margin-bottom:10px"></i>
                No staff members yet. <a href="{{ route('staff.create') }}">Add the first one.</a>
            </div>
        @else
        <div style="overflow-x:auto">
        <table class="table table-hover" style="font-size:13px">
            <thead>
                <tr style="background:#f8fafc">
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffList as $s)
                <tr>
                    <td style="color:#94a3b8">{{ $loop->iteration }}</td>
                    <td><strong>{{ $s->name }}</strong></td>
                    <td>{{ $s->email }}</td>
                    <td>
                        @if($s->is_active)
                            <span class="badge-active">Active</span>
                        @else
                            <span class="badge-inactive">Disabled</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $labels = [
                                'create_customers'=>'Customers',
                                'create_orders'=>'Orders',
                                'assign_tests'=>'Assign Tests',
                                'enter_results'=>'Results',
                                'manage_payments'=>'Payments',
                                'print_reports'=>'Reports',
                                'price_calculator'=>'Calculator',
                                'delete_tests'=>'Remove Tests',
                                'delete_orders'=>'Delete Orders',
                            ];
                        @endphp
                        @forelse($s->staff_permissions ?? [] as $p)
                            <span class="perm-chip">{{ $labels[$p] ?? $p }}</span>
                        @empty
                            <span style="color:#94a3b8;font-size:12px">None</span>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('staff.edit', $s) }}" class="btn btn-sm btn-edit">
                            <i class="feather icon-settings"></i> Manage
                        </a>
                        <form action="{{ route('staff.destroy', $s) }}" method="POST" style="display:inline" onsubmit="return confirm('Remove this staff member?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-del">
                                <i class="feather icon-trash-2"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>

</div>
</div>
</div>
</div>
@endsection
