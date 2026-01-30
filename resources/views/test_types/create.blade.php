@extends('admin_navbar')
@section('content')
<style>
    .wrap{max-width:900px;margin:0 auto}
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:22px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:all .25s ease;font-weight:900;font-size:14px}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.35)}
    .btn-ghost{background:#f1f5f9;color:#0f172a}
    .btn-ghost:hover{transform:translateY(-1px);background:#e2e8f0}

    label{display:block;font-size:12px;color:#475569;margin-bottom:6px;font-weight:900}
    input, textarea{
        width:100%;
        border:1px solid #e5e7eb;border-radius:10px;
        padding:10px 12px;font-size:14px;outline:none;background:#fff;
        transition:all .2s ease
    }
    textarea{min-height:90px;resize:vertical}
    input:focus, textarea:focus{
        border-color:rgba(37,99,235,.6);
        box-shadow:0 0 0 4px rgba(37,99,235,.12);
        transform:translateY(-1px)
    }

    .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px}
    @media(max-width:900px){.grid{grid-template-columns:1fr}}

    .toggle{display:flex;align-items:center;gap:10px;margin-top:10px;font-weight:900;color:#0f172a}
    .toggle input{width:18px;height:18px}

    .row{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
    .alert{border-radius:10px;padding:12px;margin-top:12px;font-size:13px;background:#fff1f2;color:#9f1239;border:1px solid #fecdd3}
    .hint{font-size:12px;color:#64748b;margin-top:6px}
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>

        <div class="wrap">
            <div class="card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">
                    <div>
                        <h2 style="margin:0;color:#0f172a;">Add Test Type</h2>
                        <div style="color:#64748b;font-size:13px;margin-top:4px;">
                            Test Type = priced panel/service (e.g., CBC, LFT, RFT, Ultrasound).
                        </div>
                    </div>
                    <a href="{{ route('test-types.index') }}" class="btn btn-ghost">Back</a>
                </div>

                @if($errors->any())
                    <div class="alert">
                        <b>Please fix the following:</b>
                        <ul style="margin:8px 0 0 18px;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('test-types.store') }}">
                    @csrf

                    <div class="grid">
                        <div>
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., CBC" required>
                        </div>

                        <div>
                            <label>Code (optional)</label>
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g., CBC-01">
                            <div class="hint">Optional unique code (if you want).</div>
                        </div>

                        <div>
                            <label>Price</label>
                            <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" placeholder="e.g., 1500" required>
                            <div class="hint">This is the billed price (PKR).</div>
                        </div>

                        <div>
                            <label>Description (optional)</label>
                            <textarea name="description" placeholder="Notes for staff / internal description...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="toggle">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <span>Active</span>
                    </div>

                    <div class="row">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <a class="btn btn-ghost" href="{{ route('test-types.index') }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
