{{--
    Description Editor Partial
    Usage: @include('test_types._desc_editor', ['descValue' => $value])
--}}
@php
    $dv = $descValue ?? '';
    $isTable   = false;
    $tableData = null;
    if (trim($dv) !== '' && str_starts_with(trim($dv), '{')) {
        $dec = json_decode($dv, true);
        if ($dec && ($dec['type'] ?? '') === 'table') {
            $isTable   = true;
            $tableData = $dec;
        }
    }
    $initCols = $isTable ? max(1, count($tableData['headers'] ?? [])) : 3;
    $initRows = $isTable ? max(1, count($tableData['rows']    ?? [])) : 3;
@endphp

<div class="desc-editor-wrap" style="margin-top:14px;">
    <label>Description <span style="font-weight:400;color:#94a3b8;">(optional — shown at end of test section in PDF)</span></label>

    {{-- Mode toggle --}}
    <div style="display:flex;gap:16px;padding:10px 14px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:10px;">
        <label style="display:flex;align-items:center;gap:7px;cursor:pointer;margin:0;font-size:13px;font-weight:700;color:#0f172a;">
            <input type="radio" name="desc_mode" value="text"  {{ !$isTable ? 'checked' : '' }} style="width:auto;margin:0;" onchange="descSwitchMode('text')">
            ✏️ Plain Text
        </label>
        <label style="display:flex;align-items:center;gap:7px;cursor:pointer;margin:0;font-size:13px;font-weight:700;color:#0f172a;">
            <input type="radio" name="desc_mode" value="table" {{ $isTable  ? 'checked' : '' }} style="width:auto;margin:0;" onchange="descSwitchMode('table')">
            📊 Table
        </label>
    </div>

    {{-- The actual submitted value --}}
    <input type="hidden" name="description" id="descHidden" value="{{ $dv }}">

    {{-- ── Plain text ── --}}
    <div id="descTextSection" style="{{ $isTable ? 'display:none' : '' }}">
        <textarea id="descTextarea" rows="5"
                  placeholder="Enter description text shown at the bottom of the test section in the report…"
                  style="width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font-size:14px;resize:vertical;box-sizing:border-box;outline:none;">{{ $isTable ? '' : $dv }}</textarea>
    </div>

    {{-- ── Table editor ── --}}
    <div id="descTableSection" style="{{ !$isTable ? 'display:none' : '' }}">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;margin-bottom:10px;">
            <div style="display:flex;align-items:center;gap:6px;">
                <label style="margin:0;font-size:12px;font-weight:700;white-space:nowrap;">Columns:</label>
                <input type="number" id="descTblCols" min="1" max="12" value="{{ $initCols }}"
                       style="width:64px;padding:6px 8px;text-align:center;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;outline:none;">
            </div>
            <div style="display:flex;align-items:center;gap:6px;">
                <label style="margin:0;font-size:12px;font-weight:700;white-space:nowrap;">Data rows:</label>
                <input type="number" id="descTblRows" min="1" max="40" value="{{ $initRows }}"
                       style="width:64px;padding:6px 8px;text-align:center;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;outline:none;">
            </div>
            <button type="button" onclick="descGenerateGrid()"
                    style="padding:8px 18px;border-radius:8px;border:0;background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;font-size:13px;font-weight:700;cursor:pointer;">
                ↻ Generate / Refresh Grid
            </button>
        </div>
        <div style="font-size:11px;color:#64748b;margin-bottom:8px;">
            <b>First row</b> = column headers (shown bold in the PDF). Remaining rows = data cells.
        </div>
        <div id="descTblGrid" style="overflow-x:auto;border:1px solid #e2e8f0;border-radius:10px;background:#fff;"></div>
    </div>
</div>

<script>
(function(){
    var _mode      = '{{ $isTable ? "table" : "text" }}';
    var _initData  = {!! json_encode($tableData) !!};

    /* ── Switch mode ── */
    window.descSwitchMode = function(mode){
        _mode = mode;
        document.getElementById('descTextSection').style.display  = mode === 'text'  ? 'block' : 'none';
        document.getElementById('descTableSection').style.display = mode === 'table' ? 'block' : 'none';
    };

    /* ── Build / rebuild the grid ── */
    window.descGenerateGrid = function(headers, rows){
        var cols     = parseInt(document.getElementById('descTblCols').value) || 3;
        var dataRows = parseInt(document.getElementById('descTblRows').value) || 3;

        var s = '<table style="width:100%;border-collapse:collapse;">';

        /* Header row */
        s += '<tr style="background:#eff6ff;">';
        for(var c = 0; c < cols; c++){
            var v = (headers && headers[c] != null) ? headers[c] : '';
            s += '<td style="padding:3px;border:1px solid #c7d2fe;">'
               + '<input type="text" class="dtc dth" data-r="0" data-c="'+c+'" value="'+_ea(v)+'"'
               + ' placeholder="Header '+(c+1)+'"'
               + ' style="width:100%;border:none;padding:5px 8px;font-size:12px;font-weight:900;background:transparent;outline:none;box-sizing:border-box;color:#1e40af;">'
               + '</td>';
        }
        s += '</tr>';

        /* Data rows */
        for(var r = 0; r < dataRows; r++){
            s += '<tr'+(r%2===1?' style="background:#f8fafc;"':'')+' >';
            for(var c = 0; c < cols; c++){
                var v = (rows && rows[r] && rows[r][c] != null) ? rows[r][c] : '';
                s += '<td style="padding:3px;border:1px solid #e2e8f0;">'
                   + '<input type="text" class="dtc dtd" data-r="'+(r+1)+'" data-c="'+c+'" value="'+_ea(v)+'"'
                   + ' placeholder="Row '+(r+1)+', Col '+(c+1)+'"'
                   + ' style="width:100%;border:none;padding:5px 8px;font-size:12px;background:transparent;outline:none;box-sizing:border-box;color:#111827;">'
                   + '</td>';
            }
            s += '</tr>';
        }
        s += '</table>';
        document.getElementById('descTblGrid').innerHTML = s;
    };

    /* ── Serialize grid → JSON ── */
    function _serialize(){
        var headers = [], rows = [];
        document.querySelectorAll('#descTblGrid .dtc').forEach(function(inp){
            var r = +inp.dataset.r, c = +inp.dataset.c;
            if(r === 0){ headers[c] = inp.value; }
            else { if(!rows[r-1]) rows[r-1]=[]; rows[r-1][c]=inp.value; }
        });
        return JSON.stringify({type:'table', headers:headers, rows:rows});
    }

    /* ── Escape attribute value ── */
    function _ea(s){
        return String(s||'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    /* ── On DOM ready ── */
    function _init(){
        /* Populate grid if starting in table mode */
        if(_mode === 'table' && _initData){
            document.getElementById('descTblCols').value = (_initData.headers||[]).length || 3;
            document.getElementById('descTblRows').value = (_initData.rows||[]).length    || 3;
            descGenerateGrid(_initData.headers, _initData.rows);
        }

        /* Hook form submit to write hidden value */
        var wrap = document.querySelector('.desc-editor-wrap');
        var form = wrap ? wrap.closest('form') : null;
        if(form){
            form.addEventListener('submit', function(){
                if(_mode === 'table'){
                    document.getElementById('descHidden').value = _serialize();
                } else {
                    document.getElementById('descHidden').value = document.getElementById('descTextarea').value;
                }
            });
        }
    }

    if(document.readyState === 'loading'){ document.addEventListener('DOMContentLoaded', _init); }
    else { _init(); }
})();
</script>
