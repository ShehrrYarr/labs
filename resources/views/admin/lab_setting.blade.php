@extends('admin_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:24px;max-width:960px;margin:0 auto;}
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
    .section{margin-top:28px;padding-top:20px;border-top:2px solid #e5e7eb;}
    .section-title{font-weight:900;font-size:16px;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
    .doctor-row{display:grid;grid-template-columns:1fr 1fr 36px;gap:10px;align-items:start;margin-bottom:10px;}
    .logo-preview{max-height:80px;margin-top:8px;border-radius:8px;border:1px solid #e5e7eb;}
    .muted{font-size:12px;color:#64748b;margin-top:4px;}

    /* ── Gallery ── */
    .gallery-section{margin-bottom:14px;}
    .gallery-label{font-size:12px;font-weight:900;color:#475569;margin-bottom:8px;}
    .gallery-grid{display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
    .gallery-item{position:relative;width:90px;height:90px;border-radius:10px;overflow:visible;border:2px solid #e5e7eb;cursor:pointer;background:#f8fafc;transition:border-color .15s;}
    .gallery-item:hover{border-color:#2563eb;}
    .gallery-item img{width:100%;height:100%;object-fit:contain;border-radius:8px;display:block;}
    .gallery-delete{position:absolute;top:-8px;right:-8px;width:22px;height:22px;border-radius:50%;background:#dc2626;color:#fff;border:0;cursor:pointer;font-size:12px;display:none;align-items:center;justify-content:center;font-weight:900;z-index:5;padding:0;}
    .gallery-item:hover .gallery-delete{display:flex;}
    .gallery-upload-btn{display:inline-flex;flex-direction:column;align-items:center;justify-content:center;width:90px;height:90px;border:2px dashed #94a3b8;border-radius:10px;cursor:pointer;color:#64748b;font-size:11px;font-weight:700;text-align:center;transition:all .15s;gap:4px;}
    .gallery-upload-btn:hover{border-color:#2563eb;color:#2563eb;background:#eff6ff;}
    .gallery-upload-icon{font-size:22px;}

    /* ── Canvas toolbar ── */
    .canvas-toolbar{display:flex;flex-wrap:wrap;gap:8px;margin:12px 0;}
    .tb-btn{padding:8px 14px;border-radius:8px;border:0;cursor:pointer;font-size:13px;font-weight:700;background:#f1f5f9;color:#0f172a;transition:background .15s;}
    .tb-btn:hover{background:#e2e8f0;}
    .tb-btn:disabled{opacity:.5;cursor:not-allowed;}
    .tb-primary{background:linear-gradient(135deg,#2563eb,#1e40af)!important;color:#fff!important;}
    .tb-primary:hover{background:linear-gradient(135deg,#1d4ed8,#1e3a8a)!important;}
    .tb-danger{background:#fee2e2;color:#991b1b;}
    .tb-danger:hover{background:#fecaca;}
    .tb-warning{background:#fef3c7;color:#92400e;}
    .tb-warning:hover{background:#fde68a;}
    .tb-separator{width:1px;background:#e5e7eb;margin:0 4px;}

    /* ── Canvas wrap ── */
    .canvas-wrap{overflow-x:auto;border:2px solid #e5e7eb;border-radius:10px;background:#f8fafc;padding:0;margin:0;}
    .canvas-wrap canvas{display:block;}
    .canvas-hint{font-size:11px;color:#94a3b8;margin-top:6px;}

    /* ── Composite preview ── */
    .composite-preview-wrap{margin-top:14px;}
    .composite-preview-label{font-size:12px;font-weight:900;color:#475569;margin-bottom:6px;}
    .composite-preview-img{max-width:100%;border:1px solid #e5e7eb;border-radius:8px;display:block;}

    /* ── Toast ── */
    #canvasToast{position:fixed;top:24px;right:24px;background:#065f46;color:#fff;padding:12px 20px;border-radius:10px;font-weight:700;font-size:13px;z-index:99999;display:none;box-shadow:0 4px 20px rgba(0,0,0,.2);}
</style>

<div class="app-content content">
<div class="content-wrapper" style="padding:20px;">
<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h2 style="margin:0;color:#0f172a;">⚙️ Lab Settings</h2>
        <a class="btn btn-ghost" href="{{ route('user.index') }}">Back</a>
    </div>

    @if(session('success'))
        <div class="alert">✓ {{ session('success') }}</div>
    @endif

    {{-- ════════════════════════════════════════════
         SECTION 1 — TEXT INFO + LOGO
    ═══════════════════════════════════════════════ --}}
    <form method="POST" action="{{ route('lab-settings.update') }}" enctype="multipart/form-data" id="mainForm">
        @csrf
        @method('PUT')

        <div class="section-title">🏥 Lab Information</div>

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
            <label>Logo (used as fallback when no canvas header is saved)</label>
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
            <div class="section-title">📝 Footer — Disclaimer Note</div>
            <div class="field">
                <label>Footer Note</label>
                <input type="text" name="footer_note" value="{{ old('footer_note', $setting->footer_note) }}"
                       placeholder="Electronically generated report — No need of signature">
            </div>
        </div>

        {{-- ── FOOTER 2 — DOCTORS ── --}}
        <div class="section">
            <div class="section-title">👨‍⚕️ Footer — Doctor / Staff Names</div>
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
                @endforelse
            </div>

            <button type="button" class="btn btn-ghost" style="margin-top:8px;" onclick="addDoctor()">+ Add Doctor / Staff</button>
        </div>

        <div style="margin-top:24px;">
            <button type="submit" class="btn btn-primary">💾 Save Lab Settings</button>
        </div>
    </form>

    {{-- ════════════════════════════════════════════
         SECTION 2 — CANVAS HEADER DESIGNER
    ═══════════════════════════════════════════════ --}}
    <div class="section">
        <div class="section-title">🎨 Report Header Designer</div>
        <p class="muted" style="margin-bottom:16px;font-size:13px;">
            Upload your images (logo, banner, stamp…), drag and resize them on the canvas, then click
            <strong>Save Header Design</strong>. The saved header will appear on all PDF reports.
        </p>

        {{-- Image Gallery --}}
        <div class="gallery-section">
            <div class="gallery-label">Image Gallery &nbsp;<span style="font-weight:400;color:#94a3b8;">(click a thumbnail to place it on the canvas)</span></div>
            <div class="gallery-grid" id="imageGallery">
                @foreach($setting->header_images ?? [] as $img)
                    @php $imgUrl = asset('letterheads/' . $img); @endphp
                    <div class="gallery-item" data-filename="{{ $img }}" data-url="{{ $imgUrl }}"
                         onclick="addImageToCanvas(this.dataset.url, this.dataset.filename)">
                        <img src="{{ $imgUrl }}" alt="">
                        <button type="button" class="gallery-delete"
                                onclick="event.stopPropagation(); deleteGalleryImage('{{ $img }}', this.closest('.gallery-item'))">✕</button>
                    </div>
                @endforeach
                <label class="gallery-upload-btn" for="imageUploadInput">
                    <span class="gallery-upload-icon">📁</span>
                    Upload Image
                </label>
                <input type="file" id="imageUploadInput" accept="image/*" style="display:none;">
            </div>
        </div>

        {{-- Canvas Toolbar --}}
        <div class="canvas-toolbar">
            <button type="button" class="tb-btn" onclick="addTextToCanvas()">✏️ Add Text</button>
            <div class="tb-separator"></div>
            <button type="button" class="tb-btn" onclick="bringForward()">⬆ Forward</button>
            <button type="button" class="tb-btn" onclick="sendBackward()">⬇ Backward</button>
            <div class="tb-separator"></div>
            <button type="button" class="tb-btn tb-danger" onclick="removeSelected()">🗑 Remove Selected</button>
            <button type="button" class="tb-btn tb-warning" onclick="clearCanvas()">⊗ Clear All</button>
            <div class="tb-separator"></div>
            <select id="canvasHeightSelect" onchange="resizeCanvas(this.value)"
                    style="width:auto;padding:8px 12px;border-radius:8px;font-size:13px;font-weight:700;">
                <option value="120">Height: 120px</option>
                <option value="150">Height: 150px</option>
                <option value="160" selected>Height: 160px</option>
                <option value="180">Height: 180px</option>
                <option value="200">Height: 200px</option>
                <option value="220">Height: 220px</option>
            </select>
            <div class="tb-separator"></div>
            <button type="button" class="tb-btn tb-primary" id="saveCanvasBtn" onclick="saveCanvas()">💾 Save Header Design</button>
        </div>

        {{-- Canvas --}}
        <div class="canvas-wrap">
            <canvas id="headerCanvas"></canvas>
        </div>
        <div class="canvas-hint">
            🖱 Drag to move &nbsp;·&nbsp; Corner handles to resize &nbsp;·&nbsp; Double-click text to edit &nbsp;·&nbsp; <kbd>Delete</kbd> key removes selected
        </div>

        {{-- Current Saved Preview --}}
        @php $hasComposite = file_exists(public_path('letterheads/header_composite.png')); @endphp
        <div class="composite-preview-wrap" id="compositePreviewWrap" style="{{ $hasComposite ? '' : 'display:none;' }}">
            <div class="composite-preview-label">✅ Currently Saved Header (used in all PDF reports):</div>
            <img id="compositePreviewImg"
                 src="{{ $hasComposite ? asset('letterheads/header_composite.png').'?t='.time() : '' }}"
                 class="composite-preview-img" alt="Saved Header">
        </div>
    </div>

</div><!-- /card -->
</div>
</div>

{{-- Toast notification --}}
<div id="canvasToast"></div>

{{-- Embedded canvas JSON for loading (safe via textContent) --}}
<script type="application/json" id="savedCanvasJson">
    {!! $setting->header_canvas_json
        ? str_replace('</', '<\/', $setting->header_canvas_json)
        : '{}' !!}
</script>

{{-- Fabric.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<script>
/* ─── FABRIC CANVAS INIT ─────────────────────── */
const CANVAS_W = 800;
let   CANVAS_H = 160;

const canvas = new fabric.Canvas('headerCanvas', {
    width:           CANVAS_W,
    height:          CANVAS_H,
    backgroundColor: '#ffffff',
    preserveObjectStacking: true,
});

/* Load saved canvas JSON */
(function() {
    const el   = document.getElementById('savedCanvasJson');
    const text = (el ? el.textContent : '').trim();
    if (!text || text === '{}') return;

    let json;
    try { json = JSON.parse(text); } catch(e) { console.warn('Canvas JSON parse error', e); return; }
    if (!json || !json.objects) return;

    // Fix image src URLs — stored as relative path, reconstruct with current origin
    const base = '{{ asset("letterheads/") }}';
    json.objects = json.objects.map(function(obj) {
        if (obj.type === 'image' && obj._filename) {
            obj.src = base + obj._filename;
        }
        return obj;
    });

    // Restore canvas height from saved JSON
    if (json._canvasHeight) {
        CANVAS_H = json._canvasHeight;
        canvas.setHeight(CANVAS_H);
        const sel = document.getElementById('canvasHeightSelect');
        if (sel) sel.value = String(CANVAS_H);
    }

    canvas.loadFromJSON(json, function() { canvas.renderAll(); });
})();

/* ─── KEYBOARD: Delete key removes selected ─── */
document.addEventListener('keydown', function(e) {
    if ((e.key === 'Delete' || e.key === 'Backspace') &&
        canvas.getActiveObject() &&
        !['INPUT','TEXTAREA','SELECT'].includes(document.activeElement.tagName)) {
        e.preventDefault();
        removeSelected();
    }
});

/* ─── GALLERY → CANVAS ──────────────────────── */
function addImageToCanvas(url, filename) {
    fabric.Image.fromURL(url, function(img) {
        const maxH = Math.floor(CANVAS_H * 0.88);
        const maxW = 240;
        const scale = Math.min(maxH / img.height, maxW / img.width, 1);
        img.set({
            left:   20,
            top:    Math.floor((CANVAS_H - img.height * scale) / 2),
            scaleX: scale,
            scaleY: scale,
        });
        // Store filename for URL reconstruction on next load
        img._filename = filename || null;
        canvas.add(img);
        canvas.setActiveObject(img);
        canvas.renderAll();
    });
}

/* ─── ADD TEXT ──────────────────────────────── */
function addTextToCanvas() {
    const text = new fabric.IText('Type here…', {
        left:       80,
        top:        Math.floor(CANVAS_H / 2) - 12,
        fontSize:   20,
        fontFamily: 'Arial',
        fontWeight: 'bold',
        fill:       '#111111',
    });
    canvas.add(text);
    canvas.setActiveObject(text);
    canvas.renderAll();
    text.enterEditing();
    text.selectAll();
}

/* ─── TOOLBAR ACTIONS ───────────────────────── */
function removeSelected() {
    const objs = canvas.getActiveObjects();
    if (!objs.length) return;
    objs.forEach(function(o) { canvas.remove(o); });
    canvas.discardActiveObject();
    canvas.renderAll();
}

function clearCanvas() {
    if (!confirm('Clear everything from the canvas?')) return;
    canvas.clear();
    canvas.backgroundColor = '#ffffff';
    canvas.renderAll();
}

function bringForward() {
    const obj = canvas.getActiveObject();
    if (obj) { canvas.bringForward(obj); canvas.renderAll(); }
}

function sendBackward() {
    const obj = canvas.getActiveObject();
    if (obj) { canvas.sendBackwards(obj); canvas.renderAll(); }
}

function resizeCanvas(h) {
    CANVAS_H = parseInt(h, 10);
    canvas.setHeight(CANVAS_H);
    canvas.renderAll();
}

/* ─── SAVE CANVAS ───────────────────────────── */
function saveCanvas() {
    const btn = document.getElementById('saveCanvasBtn');
    btn.disabled = true;
    btn.textContent = 'Saving…';

    // Serialize — store _filename inside each image object
    canvas.getObjects('image').forEach(function(img) {
        img.toObject = (function(toObject) {
            return function(propertiesToInclude) {
                return fabric.util.object.extend(toObject.call(this, propertiesToInclude), {
                    _filename: img._filename || null
                });
            };
        })(img.toObject);
    });

    const jsonData = canvas.toJSON(['_filename']);
    jsonData._canvasHeight = CANVAS_H;
    const canvasJson    = JSON.stringify(jsonData);
    const compositeData = canvas.toDataURL({ format: 'png', multiplier: 2 });

    fetch('{{ route("lab-settings.save-canvas") }}', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body:    JSON.stringify({ canvas_json: canvasJson, composite_data: compositeData }),
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        btn.disabled    = false;
        btn.textContent = '💾 Save Header Design';
        if (d.ok) {
            // Update preview
            const wrap = document.getElementById('compositePreviewWrap');
            const img  = document.getElementById('compositePreviewImg');
            img.src    = compositeData;
            wrap.style.display = '';
            showToast('✅ Header design saved!');
        }
    })
    .catch(function() {
        btn.disabled    = false;
        btn.textContent = '💾 Save Header Design';
        alert('Save failed — please try again.');
    });
}

/* ─── UPLOAD IMAGE ──────────────────────────── */
document.getElementById('imageUploadInput').addEventListener('change', function(e) {
    if (!e.target.files.length) return;
    const file     = e.target.files[0];
    const label    = document.querySelector('label.gallery-upload-btn');
    label.innerHTML = '<span class="gallery-upload-icon">⏳</span> Uploading…';

    const fd = new FormData();
    fd.append('image', file);
    fd.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("lab-settings.upload-image") }}', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            label.innerHTML = '<span class="gallery-upload-icon">📁</span> Upload Image';
            if (d.ok) appendGalleryItem(d.filename, d.url);
        })
        .catch(function() {
            label.innerHTML = '<span class="gallery-upload-icon">📁</span> Upload Image';
        });

    this.value = '';
});

function appendGalleryItem(filename, url) {
    const gallery   = document.getElementById('imageGallery');
    const uploadLbl = gallery.querySelector('label.gallery-upload-btn');
    const item      = document.createElement('div');
    item.className  = 'gallery-item';
    item.dataset.filename = filename;
    item.dataset.url      = url;
    item.innerHTML  = `<img src="${url}" alt=""><button type="button" class="gallery-delete">✕</button>`;
    item.addEventListener('click', function() { addImageToCanvas(url, filename); });
    item.querySelector('.gallery-delete').addEventListener('click', function(ev) {
        ev.stopPropagation();
        deleteGalleryImage(filename, item);
    });
    gallery.insertBefore(item, uploadLbl);
}

/* ─── DELETE GALLERY IMAGE ──────────────────── */
function deleteGalleryImage(filename, el) {
    if (!confirm('Remove this image from the gallery?')) return;
    fetch('{{ route("lab-settings.delete-image") }}', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body:    JSON.stringify({ filename: filename }),
    })
    .then(function(r) { return r.json(); })
    .then(function(d) { if (d.ok) el.remove(); });
}

/* ─── TOAST ─────────────────────────────────── */
function showToast(msg) {
    const t = document.getElementById('canvasToast');
    t.textContent = msg;
    t.style.display = 'block';
    setTimeout(function() { t.style.display = 'none'; }, 3000);
}

/* ─── DOCTORS FORM ──────────────────────────── */
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

if (document.querySelectorAll('.doctor-row').length === 0) addDoctor();
</script>
@endsection
