@extends('admin_navbar')
@section('content')
<style>
    .wrap{max-width:1100px;margin:0 auto}
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:22px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .header{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:all .25s ease;font-weight:900;font-size:14px}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.35)}
    .btn-ghost{background:#f1f5f9;color:#0f172a}
    .btn-ghost:hover{transform:translateY(-1px);background:#e2e8f0}
    .grid{display:grid;grid-template-columns:1.2fr .8fr;gap:14px;margin-top:16px}
    @media(max-width: 980px){.grid{grid-template-columns:1fr}}
    .section{border:1px solid #e5e7eb;border-radius:12px;padding:16px;background:#fafafa;position:relative;overflow:hidden}
    .section::before{content:"";position:absolute;inset:0;background:radial-gradient(650px 180px at 10% 0%, rgba(37,99,235,.10), transparent 60%);pointer-events:none}
    .section h4{margin:0 0 12px;font-size:15px;color:#0f172a;position:relative}
    label{display:block;font-size:12px;color:#475569;margin-bottom:6px;font-weight:900}
    input, textarea, select{
        width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;
        font-size:14px;outline:none;background:#fff;transition:all .2s ease
    }
    textarea{min-height:92px;resize:vertical}
    input:focus, textarea:focus, select:focus{
        border-color:rgba(37,99,235,.6);
        box-shadow:0 0 0 4px rgba(37,99,235,.12);
        transform:translateY(-1px)
    }
    .row2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    @media(max-width: 980px){.row2{grid-template-columns:1fr}}
    .toggle{display:flex;align-items:center;gap:10px;margin-top:8px;font-weight:900;color:#0f172a}
    .toggle input{width:18px;height:18px}
    .alert{border-radius:10px;padding:12px;margin-top:12px;font-size:13px;background:#fff1f2;color:#9f1239;border:1px solid #fecdd3}
    .imgBox{border:1px dashed #cbd5e1;border-radius:12px;background:#fff;padding:14px;display:flex;gap:12px;align-items:center}
    .preview{width:92px;height:92px;border-radius:14px;object-fit:cover;border:1px solid #e5e7eb;background:#f8fafc}
    .hint{font-size:12px;color:#64748b;margin-top:6px}
    .footer{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>

        <div class="wrap">
            <div class="card">
                <div class="header">
                    <div>
                        <h2 style="margin:0;color:#0f172a;">Add Sub Test</h2>
                        <p class="hint">Parent Test: <b>{{ $labTest->test_name }}</b> ({{ $labTest->test_code }})</p>
                    </div>
                    <a class="btn btn-ghost" href="{{ route('lab-tests.sub-tests.index', $labTest) }}">Back</a>
                </div>

                @if($errors->any())
                    <div class="alert">
                        <b>Please fix the following:</b>
                        <ul style="margin:8px 0 0 18px;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('lab-tests.sub-tests.store', $labTest) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid">
                        <div class="section">
                            <h4>Sub Test Details</h4>

                            <div class="row2">
                                <div>
                                    <label>Test Name</label>
                                    <input type="text" name="test_name" value="{{ old('test_name') }}" required>
                                </div>
                                <div>
                                    <label>Test Code (optional)</label>
                                    <input type="text" name="test_code" value="{{ old('test_code') }}">
                                </div>
                            </div>

                            <div class="row2" style="margin-top:12px;">
                                <div>
                                    <label>Test Type</label>
                                    <select name="test_type_id" required>
                                        <option value="">-- Select Type --</option>
                                        @foreach($types as $t)
                                            <option value="{{ $t->id }}" {{ old('test_type_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label>Category (optional)</label>
                                    <select name="test_category_id">
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}" {{ old('test_category_id') == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row2" style="margin-top:12px;">
                                <div>
                                    <label>Unit (optional)</label>
                                    <input type="text" name="unit" value="{{ old('unit') }}">
                                </div>
                                <div>
                                    <label>Reference Range (optional)</label>
                                    <input type="text" name="reference_range" value="{{ old('reference_range') }}">
                                </div>
                            </div>

                            <div class="row2" style="margin-top:12px;">
                                <div>
                                    <label>Reporting Time (optional)</label>
                                    <input type="text" name="reporting_time" value="{{ old('reporting_time') }}" placeholder="e.g. 6 Hours">
                                </div>
                                <div>
                                    <label>Sort Order</label>
                                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}">
                                </div>
                            </div>

                            <div style="margin-top:12px;">
                                <label>Description (optional)</label>
                                <textarea name="description">{{ old('description') }}</textarea>
                            </div>

                            <div class="row2" style="margin-top:12px;">
                                <div>
                                    <label>Test Instruction (optional)</label>
                                    <textarea name="test_instruction">{{ old('test_instruction') }}</textarea>
                                </div>
                                <div>
                                    <label>Additional Notes (optional)</label>
                                    <textarea name="additional_notes">{{ old('additional_notes') }}</textarea>
                                </div>
                            </div>

                            <div class="toggle">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <span>Active</span>
                            </div>
                        </div>

                        <div class="section">
                            <h4>Image & Equipment</h4>

                            <div style="margin-bottom:12px;">
                                <label>Test Case Image (optional)</label>
                                <div class="imgBox">
                                    <img id="preview" class="preview"
                                         src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Crect width='100%25' height='100%25' fill='%23f1f5f9'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%2394a3b8' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E">
                                    <div style="flex:1;">
                                        <input type="file" name="test_case_image" accept="image/*" onchange="previewImage(event)">
                                        <div class="hint">PNG/JPG/WEBP up to 2MB.</div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label>Required Equipment (optional)</label>
                                <select name="required_equipment_id">
                                    <option value="">-- None --</option>
                                    @foreach($equipment as $eq)
                                        <option value="{{ $eq->id }}" {{ old('required_equipment_id') == $eq->id ? 'selected' : '' }}>
                                            {{ $eq->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="hint" style="margin-top:10px;">
                                Equipment here is “single” because your schema uses required_equipment_id.
                            </div>
                        </div>
                    </div>

                    <div class="footer">
                        <button class="btn btn-primary" type="submit">Save Sub Test</button>
                        <a class="btn btn-ghost" href="{{ route('lab-tests.sub-tests.index', $labTest) }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event){
    const file = event.target.files && event.target.files[0];
    if(!file) return;
    document.getElementById('preview').src = URL.createObjectURL(file);
}
</script>
@endsection
