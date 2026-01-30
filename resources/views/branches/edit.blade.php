@extends('admin_navbar')
@section('content')
<style>
    .wrap { max-width: 950px; margin: 0 auto; }

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
        align-items:flex-start;
        gap: 12px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .title { margin: 0; font-size: 22px; color: #0f172a; }
    .sub { margin: 4px 0 0; color: #64748b; font-size: 13px; }

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

    .btn-ghost { background: #f1f5f9; color: #0f172a; }
    .btn-ghost:hover { transform: translateY(-1px); background: #e2e8f0; }

    .btn-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    .btn-danger:hover {
        background: #fecaca;
        transform: translateY(-1px);
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 16px;
    }

    @media(max-width: 820px) { .grid { grid-template-columns: 1fr; } }

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

    .field { margin-bottom: 12px; position: relative; }

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

    .toggle input { width: 18px; height: 18px; }

    .hint { font-size: 12px; color: #64748b; margin-top: 6px; }

    .mini {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .pill {
        display:inline-flex;
        align-items:center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 12px;
        font-weight: 700;
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
                <div class="pill">BRANCH #{{ $branch->id }}</div>
                <h2 class="title" style="margin-top:10px;">Edit Branch</h2>
                <p class="sub">Update branch profile and login details. Password is optional.</p>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('branches.index') }}" class="btn btn-ghost">Back</a>

                <form action="{{ route('branches.destroy', $branch) }}" method="POST"
                      onsubmit="return confirm('Delete this branch? This will also delete the branch login user.')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </div>
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

        <form method="POST" action="{{ route('branches.update', $branch) }}">
            @csrf
            @method('PUT')

            <div class="grid">
                {{-- Branch Profile --}}
                <div class="section">
                    <h4>Branch Profile</h4>

                    <div class="field">
                        <label>Branch Name</label>
                        <input type="text" name="branch_name"
                               value="{{ old('branch_name', $branch->branch_name) }}" required>
                    </div>

                    <div class="field">
                        <label>Phone (optional)</label>
                        <input type="text" name="phone"
                               value="{{ old('phone', $branch->phone) }}">
                    </div>

                    <div class="field">
                        <label>Address (optional)</label>
                        <textarea name="address">{{ old('address', $branch->address) }}</textarea>
                    </div>

                    <div class="toggle">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                        <span>Active Branch</span>
                    </div>
                    <div class="hint">If inactive, you can block branch access later in middleware.</div>
                </div>

                {{-- Branch Login --}}
                <div class="section">
                    <h4>Branch Login</h4>

                    <div class="field">
                        <label>Login Name</label>
                        <input type="text" name="name"
                               value="{{ old('name', $branch->user->name) }}" required>
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email"
                               value="{{ old('email', $branch->user->email) }}" required>
                        <div class="mini">This email is used to login.</div>
                    </div>

                    <div class="field">
                        <label>New Password (optional)</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current password">
                        <div class="hint">Only change if needed.</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('branches.index') }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
