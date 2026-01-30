@extends('admin_navbar')
@section('content')
<style>
    .wrap{max-width:1100px;margin:0 auto}
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:22px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

    .header{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap}
    .title{margin:0;color:#0f172a;font-size:22px}
    .sub{margin:4px 0 0;color:#64748b;font-size:13px}

    .btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;transition:all .25s ease;font-weight:800;font-size:14px}
    .btn-primary{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff}
    .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(37,99,235,.35)}
    .btn-ghost{background:#f1f5f9;color:#0f172a}
    .btn-ghost:hover{transform:translateY(-1px);background:#e2e8f0}

    .grid{display:grid;grid-template-columns:1.2fr .8fr;gap:14px;margin-top:16px}
    @media(max-width: 980px){.grid{grid-template-columns:1fr}}

    .section{border:1px solid #e5e7eb;border-radius:12px;padding:16px;background:#fafafa;position:relative;overflow:hidden}
    .section::before{content:"";position:absolute;inset:0;background:radial-gradient(650px 180px at 10% 0%, rgba(37,99,235,.10), transparent 60%);pointer-events:none}
    .section h4{margin:0 0 12px;font-size:15px;color:#0f172a;position:relative}

    .field{margin-bottom:12px;position:relative}
    label{display:block;font-size:12px;color:#475569;margin-bottom:6px;font-weight:800}
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

    .toggle{display:flex;align-items:center;gap:10px;margin-top:8px;font-weight:800;color:#0f172a}
    .toggle input{width:18px;height:18px}

    .alert{border-radius:10px;padding:12px;margin-top:12px;font-size:13px;background:#fff1f2;color:#9f1239;border:1px solid #fecdd3}

    .imgBox{
        border:1px dashed #cbd5e1;border-radius:12px;background:#fff;padding:14px;
        display:flex;gap:12px;align-items:center
    }
    .preview{
        width:92px;height:92px;border-radius:14px;object-fit:cover;border:1px solid #e5e7eb;background:#f8fafc;
        transition:transform .25s ease, box-shadow .25s ease;
    }
    .preview:hover{transform:scale(1.05);box-shadow:0 10px 18px rgba(0,0,0,.12)}
    .hint{font-size:12px;color:#64748b;margin-top:6px}

    .chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
    .chip{background:#eef2ff;color:#3730a3;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:900}

    .footer{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}

    .price-pill{
        display:inline-flex;align-items:center;justify-content:center;
        padding:8px 10px;border-radius:999px;background:#ecfeff;color:#0e7490;
        border:1px solid #cffafe;font-weight:950;font-size:12px;
        white-space:nowrap;
    }
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>

        <div class="wrap">
            <div class="card">
                <div class="header">
                    <div>
                        <h2 class="title">Add Lab Test (Main)</h2>
                        <p class="sub">Price is taken from <b>Test Type</b>. Lab Test itself has no price.</p>
                    </div>
                    <a class="btn btn-ghost" href="{{ route('lab-tests.index') }}">Back</a>
                </div>

                @if($errors->any())
                    <div class="alert">
                        <b>Please fix the following:</b>
                        <ul style="margin:8px 0 0 18px;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('lab-tests.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid">
                        {{-- Left --}}
                        <div class="section">
                            <h4>Test Details</h4>

                            <div class="row2">
                                <div class="field">
                                    <label>Test Name</label>
                                    <input type="text" name="test_name" value="{{ old('test_name') }}" required>
                                </div>

                                <div class="field">
                                    <label>Test Code</label>
                                    <input type="text" name="test_code" value="{{ old('test_code') }}" required>
                                </div>
                            </div>

                            <div class="row2">
                                <div class="field">
                                    <label>Test Type (Price comes from here)</label>
                                    <select id="test_type_id" name="test_type_id" required>
                                        <option value="">-- Select Type --</option>
                                        @foreach($types as $type)
                                            <option
                                                value="{{ $type->id }}"
                                                data-price="{{ (float)($type->price ?? 0) }}"
                                                {{ old('test_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="hint" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                        <span class="price-pill">Type Price: PKR <span id="typePriceText">0.00</span></span>
                                        <span>Lab Test has no price.</span>
                                    </div>
                                </div>

                                <div class="field">
                                    <label>Category</label>
                                    <select name="test_category_id" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('test_category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row2">
                                <div class="field">
                                    <label>Test Reporting Time</label>
                                    <input type="text" name="reporting_time" value="{{ old('reporting_time') }}" required placeholder="e.g. 6 Hours / 24 Hours / 2 Days">
                                </div>

                                <div class="field">
                                    <label>Unit (optional)</label>
                                    <input type="text" name="unit" value="{{ old('unit') }}" placeholder="e.g. mg/dl">
                                </div>
                            </div>

                            <div class="field">
                                <label>Reference Range (optional)</label>
                                <input type="text" name="reference_range" value="{{ old('reference_range') }}" placeholder="e.g. 10-50">
                            </div>

                            <div class="field">
                                <label>Description (optional)</label>
                                <textarea name="description">{{ old('description') }}</textarea>
                            </div>

                            <div class="row2">
                                <div class="field">
                                    <label>Test Instruction (optional)</label>
                                    <textarea name="test_instruction">{{ old('test_instruction') }}</textarea>
                                </div>

                                <div class="field">
                                    <label>Additional Notes (optional)</label>
                                    <textarea name="additional_notes">{{ old('additional_notes') }}</textarea>
                                </div>
                            </div>

                            <div class="toggle">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <span>Active</span>
                            </div>
                            <div class="hint">Inactive tests can be hidden later.</div>
                        </div>

                        {{-- Right --}}
                        <div class="section">
                            <h4>Image & Equipment</h4>

                            <div class="field">
                                <label>Test Case Image (optional)</label>
                                <div class="imgBox">
                                    <img id="preview" class="preview"
                                         src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Crect width='100%25' height='100%25' fill='%23f1f5f9'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%2394a3b8' font-size='14'%3ENo Image%3C/text%3E%3C/svg%3E"
                                         alt="preview">
                                    <div style="flex:1;">
                                        <input type="file" name="test_case_image" accept="image/*" onchange="previewImage(event)">
                                        <div class="hint">PNG/JPG/WEBP up to 2MB.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label>Required Equipment (optional)</label>
                                <select name="equipment_ids[]" multiple size="8" id="equipmentSelect">
                                    @foreach($equipment as $eq)
                                        <option value="{{ $eq->id }}"
                                            {{ collect(old('equipment_ids', []))->contains($eq->id) ? 'selected' : '' }}>
                                            {{ $eq->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="hint">Hold Ctrl/Command to select multiple.</div>
                            </div>

                            <div class="hint" style="margin-top:8px;font-weight:800;color:#0f172a;">Selected equipment:</div>
                            <div id="chips" class="chips"></div>
                        </div>
                    </div>

                    <div class="footer">
                        <button class="btn btn-primary" type="submit">Save Test</button>
                        <a class="btn btn-ghost" href="{{ route('lab-tests.index') }}">Cancel</a>
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
        const img = document.getElementById('preview');
        img.src = URL.createObjectURL(file);
    }

    // Equipment chips
    const select = document.getElementById('equipmentSelect');
    const chips = document.getElementById('chips');
    function renderChips(){
        if(!select || !chips) return;
        chips.innerHTML = '';
        Array.from(select.selectedOptions).forEach(opt => {
            const span = document.createElement('span');
            span.className = 'chip';
            span.textContent = opt.text;
            chips.appendChild(span);
        });
    }
    if(select){
        select.addEventListener('change', renderChips);
        renderChips();
    }

    // Show selected Test Type price (read-only display)
    const typeSelect = document.getElementById('test_type_id');
    const typePriceText = document.getElementById('typePriceText');
    function renderTypePrice(){
        if(!typeSelect || !typePriceText) return;
        const opt = typeSelect.options[typeSelect.selectedIndex];
        const p = opt ? parseFloat(opt.getAttribute('data-price') || '0') : 0;
        typePriceText.textContent = isFinite(p) ? p.toFixed(2) : '0.00';
    }
    if(typeSelect){
        typeSelect.addEventListener('change', renderTypePrice);
        renderTypePrice(); // initial
    }
</script>
@endsection
