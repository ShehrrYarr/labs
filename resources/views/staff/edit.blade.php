@extends('admin_navbar')
@section('content')
<style>
    .wrap{padding:18px}
    .card{background:#fff;border-radius:14px;padding:24px;box-shadow:0 8px 24px rgba(0,0,0,.08);border:1px solid #eef2f7;margin-bottom:16px}
    .form-group{margin-bottom:16px}
    label{display:block;font-size:13px;font-weight:800;color:#374151;margin-bottom:6px}
    .form-control{width:100%;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s;box-sizing:border-box}
    .form-control:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;border:0;cursor:pointer;font-weight:900;font-size:14px;text-decoration:none;transition:all .2s}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 16px rgba(37,99,235,.3);color:#fff}
    .btn-ghost{background:#f1f5f9;color:#374151}
    .btn-ghost:hover{background:#e2e8f0}
    .btn-success{background:linear-gradient(135deg,#10b981,#059669);color:#fff}
    .btn-success:hover{transform:translateY(-1px);box-shadow:0 8px 16px rgba(16,185,129,.3);color:#fff}
    .error{color:#dc2626;font-size:12px;margin-top:4px}
    .perm-row{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:8px;transition:background .15s}
    .perm-row:hover{background:#f8fafc}
    .perm-label{font-size:14px;font-weight:700;color:#0f172a}
    .perm-desc{font-size:12px;color:#64748b;margin-top:2px}
    /* Toggle switch */
    .toggle{position:relative;display:inline-block;width:46px;height:26px}
    .toggle input{opacity:0;width:0;height:0}
    .slider{position:absolute;cursor:pointer;inset:0;background:#e5e7eb;border-radius:999px;transition:.2s}
    .slider:before{content:"";position:absolute;width:20px;height:20px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 4px rgba(0,0,0,.15)}
    input:checked + .slider{background:#2563eb}
    input:checked + .slider:before{transform:translateX(20px)}
    .section-title{font-size:15px;font-weight:950;color:#0f172a;margin-bottom:4px}
    .section-sub{font-size:13px;color:#64748b;margin-bottom:16px}
    @keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
    .wrap>*{animation:fadeIn .4s ease}
</style>

<div class="app-content content">
<div class="content-wrapper">
<div class="content-body">
<div class="wrap">

    <div style="margin-bottom:14px">
        <a href="{{ route('staff.index') }}" style="color:#64748b;font-size:13px;text-decoration:none">
            <i class="feather icon-arrow-left"></i> Back to Staff
        </a>
    </div>

    @if(session('success'))
        <div style="background:#ecfdf5;color:#065f46;padding:12px 16px;border-radius:10px;margin-bottom:14px;font-weight:700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Account Details -->
    <div class="card">
        <div class="section-title">Account Details</div>
        <div class="section-sub">Update name, email, and password for {{ $staff->name }}</div>

        <form action="{{ route('staff.update', $staff) }}" method="POST">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>New Password <span style="color:#94a3b8;font-weight:600">(leave blank to keep current)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters">
                    @error('password')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Account Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $staff->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$staff->is_active ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary">
                    <i class="feather icon-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Permissions -->
    <div class="card">
        <div class="section-title">Permissions</div>
        <div class="section-sub">Toggle which actions {{ $staff->name }} is allowed to perform.</div>

        <form action="{{ route('staff.permissions', $staff) }}" method="POST">
            @csrf

            @php
                $currentPerms = $staff->staff_permissions ?? [];
            @endphp

            @foreach($allPermissions as $key => $label)
            @php
                $descs = [
                    'create_customers' => 'Can register new patients and edit existing customer profiles.',
                    'create_orders'    => 'Can create new test orders and manage existing orders.',
                    'assign_tests'     => 'Can assign test types to orders.',
                    'enter_results'    => 'Can enter and post test results.',
                    'manage_payments'  => 'Can record payments and apply discounts to invoices.',
                    'print_reports'    => 'Can print/download PDF reports, invoice slips, and thermal receipts.',
                    'price_calculator' => 'Can access the price calculator tool.',
                    'delete_tests'     => 'Can remove an assigned test type from an order.',
                    'delete_orders'    => 'Can permanently delete an entire order including its invoice and payments.',
                ];
            @endphp
            <div class="perm-row">
                <div>
                    <div class="perm-label">{{ $label }}</div>
                    <div class="perm-desc">{{ $descs[$key] ?? '' }}</div>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="permissions[]" value="{{ $key }}"
                        {{ in_array($key, $currentPerms) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
            @endforeach

            <div style="margin-top:16px">
                <button type="submit" class="btn btn-success">
                    <i class="feather icon-check-circle"></i> Save Permissions
                </button>
            </div>
        </form>
    </div>

</div>
</div>
</div>
</div>
@endsection
