@extends('admin_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:24px;max-width:860px;margin:0 auto;}
    .btn{display:inline-flex;align-items:center;padding:10px 20px;border-radius:10px;border:0;cursor:pointer;font-weight:900;font-size:14px;text-decoration:none;}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;}
    .btn-ghost{background:#f1f5f9;color:#0f172a;}
    .btn-danger{background:#fee2e2;color:#991b1b;padding:6px 10px;font-size:12px;border-radius:8px;}
    label{display:block;font-size:12px;color:#475569;margin-bottom:5px;font-weight:900;}
    input,textarea,select{width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font-size:14px;outline:none;background:#fff;box-sizing:border-box;}
    input:focus,textarea:focus{border-color:rgba(37,99,235,.6);box-shadow:0 0 0 4px rgba(37,99,235,.12);}
    textarea{min-height:80px;resize:vertical;}
    .field{margin-bottom:16px;}
    .row2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .alert{background:#ecfdf5;color:#065f46;padding:12px;border-radius:10px;margin-bottom:16px;}
    .section{margin-top:24px;padding-top:16px;border-top:1px solid #e5e7eb;}
    .section-title{font-weight:900;font-size:15px;color:#0f172a;margin-bottom:14px;}
    .doctor-row{display:grid;grid-template-columns:1fr 1fr 36px;gap:10px;align-items:start;margin-bottom:10px;}
    .logo-preview{max-height:80px;margin-top:8px;border-radius:8px;border:1px solid #e5e7eb;}
    .muted{font-size:12px;color:#64748b;margin-top:4px;}
</style>

<div class="app-content content">
<div class="content-wrapper" style="padding:20px;">
<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h2 style="margin:0;color:#0f172a;">Lab Settings</h2>
        <a class="btn btn-ghost" href="{{ route('user.index') }}">Back</a>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('lab-settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── HEADER INFO ── --}}
        <div class="section-title">Header Information</div>

        <div class="row2">
            <div class="field">
                <label>Lab Name *</label>
                <input type="text" name="lab_name" value="{{ old('lab_name', $setting->lab_name) }}" required>
            </div>
            <div class="field">
                <label>Contact Number</label>
                <input type="text" name="lab_phone" value="{{ old('lab_phone', $setting->lab_phone) }}" placeholder="+92 300 0000000">
            </div>
        </div>

        <div class="row2">
            <div class="field">
                <label>Email / Gmail</label>
                <input type="email" name="lab_email" value="{{ old('lab_email', $setting->lab_email) }}" placeholder="lab@example.com">
            </div>
            <div class="field">
                <label>Address</label>
                <textarea name="lab_address" placeholder="Full lab address...">{{ old('lab_address', $setting->lab_address) }}</textarea>
            </div>
        </div>

        <div class="field">
            <label>Logo</label>
            <input type="file" name="logo" accept="image/*">
            @if($setting->logo && file_exists(public_path('letterheads/' . $setting->logo)))
                <img class="logo-preview" src="{{ asset('letterheads/' . $setting->logo) }}" alt="Current Logo">
                <div class="muted">Current logo shown above. Upload a new one to replace it.</div>
            @else
                <div class="muted">No logo uploaded yet.</div>
            @endif
        </div>

        {{-- ── FOOTER 1 ── --}}
        <div class="section">
            <div class="section-title">Footer — Disclaimer Note</div>
            <div class="field">
                <label>Footer Note</label>
                <input type="text" name="footer_note" value="{{ old('footer_note', $setting->footer_note) }}"
                       placeholder="Electronically generated report — No need of signature">
            </div>
        </div>

        {{-- ── FOOTER 2 — DOCTORS ── --}}
        <div class="section">
            <div class="section-title">Footer — Doctor / Staff Names</div>
            <div class="muted" style="margin-bottom:12px;">Each doctor appears as a column in the report footer. Name is bold; description is small text below.</div>

            <div id="doctorList">
                @php $doctors = $setting->doctors ?? []; @endphp
                @forelse($doctors as $i => $doc)
                    <div class="doctor-row">
                        <div>
                            <label>Name</label>
                            <input type="text" name="doctor_names[]" value="{{ $doc['name'] ?? '' }}" placeholder="Dr. Name">
                        </div>
                        <div>
                            <label>Description / Qualifications</label>
                            <textarea name="doctor_descs[]" style="min-height:56px;" placeholder="MBBS, MPhil...">{{ $doc['description'] ?? '' }}</textarea>
                        </div>
                        <div style="padding-top:20px;">
                            <button type="button" class="btn btn-danger" onclick="this.closest('.doctor-row').remove()">✕</button>
                        </div>
                    </div>
                @empty
                    {{-- empty list — JS will add rows --}}
                @endforelse
            </div>

            <button type="button" class="btn btn-ghost" style="margin-top:8px;" onclick="addDoctor()">+ Add Doctor / Staff</button>
        </div>

        <div style="margin-top:24px;">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>
</div>
</div>

<script>
function addDoctor() {
    const row = document.createElement('div');
    row.className = 'doctor-row';
    row.innerHTML = `
        <div>
            <label>Name</label>
            <input type="text" name="doctor_names[]" placeholder="Dr. Name">
        </div>
        <div>
            <label>Description / Qualifications</label>
            <textarea name="doctor_descs[]" style="min-height:56px;" placeholder="MBBS, MPhil..."></textarea>
        </div>
        <div style="padding-top:20px;">
            <button type="button" class="btn btn-danger" onclick="this.closest('.doctor-row').remove()">✕</button>
        </div>
    `;
    document.getElementById('doctorList').appendChild(row);
}

// Add one empty row if list is empty
if (document.querySelectorAll('.doctor-row').length === 0) addDoctor();
</script>
@endsection
