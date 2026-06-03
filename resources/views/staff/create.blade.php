@extends('admin_navbar')
@section('content')
<style>
    .wrap{padding:18px;max-width:600px}
    .card{background:#fff;border-radius:14px;padding:24px;box-shadow:0 8px 24px rgba(0,0,0,.08);border:1px solid #eef2f7}
    .form-group{margin-bottom:16px}
    label{display:block;font-size:13px;font-weight:800;color:#374151;margin-bottom:6px}
    .form-control{width:100%;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;transition:border-color .2s}
    .form-control:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;border:0;cursor:pointer;font-weight:900;font-size:14px;text-decoration:none;transition:all .2s}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 16px rgba(37,99,235,.3);color:#fff}
    .btn-ghost{background:#f1f5f9;color:#374151}
    .btn-ghost:hover{background:#e2e8f0}
    .error{color:#dc2626;font-size:12px;margin-top:4px}
    @keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
    .wrap{animation:fadeIn .4s ease}
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

    <div class="card">
        <h4 style="margin:0 0 6px;font-weight:950;color:#0f172a">Add Staff Member</h4>
        <p style="color:#64748b;font-size:13px;margin-bottom:20px">Create a new staff account. You can configure permissions after creation.</p>

        <form action="{{ route('staff.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Ali Hassan" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="staff@alghanilab.com" required>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:10px;margin-top:20px">
                <button type="submit" class="btn btn-primary">
                    <i class="feather icon-user-check"></i> Create Staff
                </button>
                <a href="{{ route('staff.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>

</div>
</div>
</div>
</div>
@endsection
