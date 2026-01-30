@extends('admin_navbar')
@section('content')
<style>
    .wrap {
        max-width: 950px;
        margin: 0 auto;
    }

    .card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        padding: 22px;
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header {
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .title {
        margin: 0;
        font-size: 22px;
        color: #0f172a;
    }

    .sub {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        border: 0;
        cursor: pointer;
        transition: all 0.25s ease;
        font-weight: 600;
        font-size: 14px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #fff;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37,99,235,0.35);
    }

    .btn-ghost {
        background: #f1f5f9;
        color: #0f172a;
    }
    .btn-ghost:hover {
        transform: translateY(-1px);
        background: #e2e8f0;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 16px;
    }

    @media(max-width: 820px) {
        .grid { grid-template-columns: 1fr; }
    }

    .section {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        background: #fafafa;
        position: relative;
        overflow: hidden;
    }

    .section::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(600px 160px at 10% 0%, rgba(37,99,235,0.10), transparent 60%);
        pointer-events: none;
    }

    .section h4 {
        margin: 0 0 12px;
        font-size: 15px;
        color: #0f172a;
        position: relative;
    }

    .field {
        margin-bottom: 12px;
        position: relative;
    }

    label {
        display:block;
        font-size: 12px;
        color: #475569;
        margin-bottom: 6px;
        font-weight: 600;
    }

    input, textarea {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        background: #fff;
        transition: all 0.2s ease;
    }

    input:focus, textarea:focus {
        border-color: rgba(37,99,235,0.6);
        box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
        transform: translateY(-1px);
    }

    textarea { min-height: 92px; resize: vertical; }

    .row {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .alert {
        border-radius: 10px;
        padding: 12px;
        margin: 12px 0 0;
        font-size: 13px;
    }

    .alert-danger {
        background: #fff1f2;
        color: #9f1239;
        border: 1px solid #fecdd3;
    }

    .toggle {
        display:flex;
        align-items:center;
        gap: 10px;
        user-select:none;
        font-size: 13px;
        color: #0f172a;
        font-weight: 600;
    }

    .toggle input {
        width: 18px;
        height: 18px;
    }

    .hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
    }
</style>
<div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
<div class="wrap">
    <div class="card">
        <div class="header">
            <div>
                <h2 class="title">Create Branch</h2>
                <p class="sub">Create a branch profile + generate branch login (stored in users with category = branch).</p>
            </div>
            <a href="{{ route('branches.index') }}" class="btn btn-ghost">Back</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <b>Please fix the following:</b>
                <ul style="margin: 8px 0 0 18px;">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('branches.store') }}">
            @csrf

            <div class="grid">
                {{-- Branch Profile --}}
                <div class="section">
                    <h4>Branch Profile</h4>

                    <div class="field">
                        <label>Branch Name</label>
                        <input type="text" name="branch_name" value="{{ old('branch_name') }}" required>
                    </div>

                    <div class="field">
                        <label>Phone (optional)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}">
                    </div>

                    <div class="field">
                        <label>Address (optional)</label>
                        <textarea name="address">{{ old('address') }}</textarea>
                    </div>

                    <div class="toggle">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span>Active Branch</span>
                    </div>
                    <div class="hint">Inactive branches can’t be used later (we’ll enforce that in login/modules).</div>
                </div>

                {{-- Branch Login --}}
                <div class="section">
                    <h4>Branch Login</h4>

                    <div class="field">
                        <label>Login Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="field">
                        <label>Password</label>
                        <input type="password" name="password" required>
                        <div class="hint">Use a strong password (min 6 chars).</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <button type="submit" class="btn btn-primary">Create Branch</button>
                <a href="{{ route('branches.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
