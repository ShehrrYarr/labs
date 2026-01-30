@extends('admin_navbar')
@section('content')

<style>
    .wrap{max-width:1000px;margin:0 auto}
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:22px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

    .header{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap}
    .title{margin:0;color:#0f172a;font-size:22px}
    .sub{margin:4px 0 0;color:#64748b;font-size:13px}

    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:all .25s ease;font-weight:900;font-size:14px}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.35)}
    .btn-ghost{background:#f1f5f9;color:#0f172a}
    .btn-ghost:hover{transform:translateY(-1px);background:#e2e8f0}

    .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:16px}
    @media(max-width: 980px){.grid{grid-template-columns:1fr}}

    .section{border:1px solid #e5e7eb;border-radius:12px;padding:16px;background:#fafafa;position:relative;overflow:hidden}
    .section::before{content:"";position:absolute;inset:0;background:radial-gradient(650px 180px at 10% 0%, rgba(37,99,235,.10), transparent 60%);pointer-events:none}
    .section h4{margin:0 0 12px;font-size:15px;color:#0f172a;position:relative}

    .field{margin-bottom:12px}
    label{display:block;font-size:12px;color:#475569;margin-bottom:6px;font-weight:900}
    input, textarea, select{
        width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font-size:14px;
        outline:none;background:#fff;transition:all .2s ease
    }
    textarea{min-height:92px;resize:vertical}
    input:focus, textarea:focus, select:focus{
        border-color:rgba(37,99,235,.6);
        box-shadow:0 0 0 4px rgba(37,99,235,.12);
        transform:translateY(-1px)
    }

    .toggle{display:flex;align-items:center;gap:10px;margin-top:8px;font-weight:900;color:#0f172a}
    .toggle input{width:18px;height:18px}

    .alert{border-radius:10px;padding:12px;margin-top:12px;font-size:13px;background:#fff1f2;color:#9f1239;border:1px solid #fecdd3}
    .footer{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
    .hint{font-size:12px;color:#64748b;margin-top:6px}
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>

        <div class="wrap">
            <div class="card">

                <div class="header">
                    <div>
                        <h2 class="title">Add Customer</h2>
                        <p class="sub">Customer login will be created automatically (ID + password).</p>
                    </div>
                    <a class="btn btn-ghost" href="{{ route('customers.index') }}">Back</a>
                </div>

                @if($errors->any())
                    <div class="alert">
                        <b>Please fix the following:</b>
                        <ul style="margin:8px 0 0 18px;">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf

                    <div class="grid">

                        {{-- Customer Login --}}
                        <div class="section">
                            <h4>Customer Login</h4>

                            <div class="field">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="hint">
                                Login ID and Password will be generated automatically after saving.
                            </div>
                        </div>

                        {{-- Customer Profile --}}
                        <div class="section">
                            <h4>Customer Profile</h4>

                            <div class="field">
                                <label>Phone (optional)</label>
                                <input type="text" name="phone" value="{{ old('phone') }}">
                            </div>

                            <div class="field">
                                <label>Address (optional)</label>
                                <textarea name="address">{{ old('address') }}</textarea>
                            </div>

                            <div class="field">
                                <label>Date of Birth (optional)</label>
                                <input type="date" name="dob" value="{{ old('dob') }}">
                            </div>

                            <div class="field">
                                <label>Gender (optional)</label>
                                <select name="gender">
                                    <option value="">-- Select --</option>
                                    <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender')=='other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="field">
                                <label>Ref by (optional)</label>
                                <input type="text" name="ref_by" value="{{ old('ref_by') }}">
                            </div>

                            <div class="toggle">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <span>Active</span>
                            </div>
                        </div>

                    </div>

                    <div class="footer">
                        <button class="btn btn-primary" type="submit">Save Customer</button>
                        <a class="btn btn-ghost" href="{{ route('customers.index') }}">Cancel</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
