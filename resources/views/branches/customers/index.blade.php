@extends('branches.branch_navbar')
@section('content')
<style>
    .card{background:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,.08);padding:20px;animation:fadeIn .6s ease}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
    .btn{background:linear-gradient(135deg,#2563eb,#1e40af);color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;transition:all .3s ease;font-weight:800}
    .btn:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(37,99,235,.35)}
    .muted{color:#64748b;font-size:13px;margin-top:4px}

    .alert{background:#ecfdf5;color:#065f46;padding:12px;border-radius:8px;margin-top:12px}

    table{width:100%;border-collapse:collapse;margin-top:15px}
    th{background:#f1f5f9;text-align:left;padding:12px;font-size:13px;color:#334155}
    td{padding:12px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:middle}
    tr:hover{background:#f8fafc;transition:background .2s ease}

    .badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:900}
    .active{background:#dcfce7;color:#166534}
    .inactive{background:#fee2e2;color:#991b1b}

    .actions a,.actions button{margin-right:10px;font-size:13px;color:#2563eb;background:none;border:none;cursor:pointer;font-weight:800}
    .actions a:hover,.actions button:hover{text-decoration:underline}

    .iconbtn{
        display:inline-flex;align-items:center;justify-content:center;
        width:34px;height:34px;border-radius:10px;
        background:#eef2ff;color:#3730a3;text-decoration:none;
        transition:all .25s ease;border:1px solid #e5e7eb;
        font-weight:900;
    }
    .iconbtn:hover{transform:translateY(-2px);box-shadow:0 10px 18px rgba(0,0,0,.10)}
    .small{font-size:12px;color:#64748b}

    /* Search */
    .search-wrap{
        margin-top:14px;
        display:flex;
        gap:10px;
        align-items:center;
        flex-wrap:wrap;
        background:#f8fafc;
        border:1px solid #e5e7eb;
        padding:12px;
        border-radius:12px;
    }
    .search-input{
        flex:1;
        min-width:260px;
        border:1px solid #e5e7eb;
        border-radius:10px;
        padding:10px 12px;
        outline:none;
        font-size:14px;
        background:#fff;
        transition:all .2s ease;
    }
    .search-input:focus{
        border-color:rgba(37,99,235,.55);
        box-shadow:0 0 0 4px rgba(37,99,235,.12);
        transform:translateY(-1px)
    }
    .chip{
        display:inline-flex;gap:6px;align-items:center;
        background:#eef2ff;color:#3730a3;
        padding:6px 10px;border-radius:999px;
        font-size:12px;font-weight:900;
        border:1px solid #e5e7eb;
        white-space:nowrap;
    }
    .clear-btn{
        background:#fff;border:1px solid #e5e7eb;border-radius:10px;
        padding:10px 12px;cursor:pointer;font-weight:900;color:#0f172a;
        transition:all .2s ease;
    }
    .clear-btn:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(0,0,0,.08)}
    .hidden{display:none!important;}
</style>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>

        <div class="card">
            <div class="top">
                <div>
                    <h2 style="margin:0;">Customers</h2>
                    <div class="muted">
                        @if(auth()->user()->category === 'admin')
                            Admin view: you can see customers and which branch/user created them.
                        @else
                            Branch view: you see only your created customers.
                        @endif
                    </div>
                </div>
                <a class="btn" href="{{ route('customers.create') }}">+ Add Customer</a>
            </div>

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            <div class="search-wrap">
                <input id="customerSearch"
                       class="search-input"
                       type="text"
                       placeholder="Search across all customers by name, login id, phone, ref by..."
                       autocomplete="off">
                <span id="matchChip" class="chip hidden">0 matches</span>
                <button id="clearSearch" class="clear-btn hidden" type="button">Clear</button>
            </div>

            <table>
                <thead>
                <tr>
                    <th style="width:70px;">Orders History</th>
                    <th style="width:70px;">Test History</th>
                    <th>Customer</th>
                    <th>Login Id</th>
                    <th>Password</th>
                    <th>Phone</th>

                    @if(auth()->user()->category === 'admin')
                        <th>Added By Branch</th>
                    @endif

                    <th>Status</th>
                    <th>Ref By</th>
                    <th style="width:200px;">Actions</th>
                </tr>
                </thead>

                <tbody id="customersTbody">
                @forelse($customers as $c)
                    <tr>
                        <td>
                            <a class="iconbtn" href="{{ route('customers.tests', $c) }}" title="Orders / Tests">🧾</a>
                            <div class="small" style="margin-top:4px;">History</div>
                        </td>

                        <td>
                            <a class="iconbtn" href="{{ route('customers.test_history', $c) }}" title="All Tests Table">🧪</a>
                            <div class="small" style="margin-top:4px;">History</div>
                        </td>

                        <td>
                            <div style="font-weight:900;color:#0f172a;">{{ $c->user->name ?? 'Deleted'}}</div>
                            <div class="small">ID: {{ $c->id }}</div>
                        </td>

                        <td>{{ $c->user->login_id ?? 'Deleted' }}</td>
                        <td>{{ $c->user->password_text ?? '-' }}</td>
                        <td>{{ $c->phone ?? '-' }}</td>

                        @if(auth()->user()->category === 'admin')
                            <td>{{ $c->createdByBranch?->branch_name ?? 'Admin / Unknown' }}</td>
                        @endif

                        <td>
                            @if($c->is_active)
                                <span class="badge active">Active</span>
                            @else
                                <span class="badge inactive">Inactive</span>
                            @endif
                        </td>

                        <td>{{ $c->ref_by ?? '-' }}</td>

                        <td class="actions">
                            <a href="{{ route('customers.edit', $c) }}">Edit</a>

                            {{-- <form action="{{ route('customers.destroy', $c) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete this customer? This will also delete their login user.')">Delete</button>
                            </form> --}}
                        </td>
                    </tr>
                @empty
                    @php $colspan = auth()->user()->category === 'admin' ? 10 : 9; @endphp
                    <tr><td colspan="{{ $colspan }}">No customers found.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px;" id="paginationWrap">
                {{ $customers->links() }}
            </div>

            <div id="noMatchRow" class="muted hidden" style="margin-top:12px;">
                No matching customers found.
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const input = document.getElementById('customerSearch');
    const clearBtn = document.getElementById('clearSearch');
    const chip = document.getElementById('matchChip');
    const tbody = document.getElementById('customersTbody');
    const pagination = document.getElementById('paginationWrap');
    const noMatch = document.getElementById('noMatchRow');

    const isAdmin = @json(auth()->user()->category === 'admin');
    const csrf = @json(csrf_token());

    const originalTbodyHTML = tbody.innerHTML;

    function setHidden(el, hide){
        if(!el) return;
        el.classList.toggle('hidden', hide);
    }

    function escHtml(s){
        return String(s ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function statusBadgeHtml(isActive){
        return isActive
            ? `<span class="badge active">Active</span>`
            : `<span class="badge inactive">Inactive</span>`;
    }

    function rowHtml(c){
        const testsUrl = @json(url('/customers')) + '/' + encodeURIComponent(c.id) + '/tests';
        const testHistoryUrl = @json(url('/customers')) + '/' + encodeURIComponent(c.id) + '/test-history';
        const editUrl  = @json(url('/customers')) + '/' + encodeURIComponent(c.id) + '/edit';
        const deleteUrl = @json(url('/customers')) + '/' + encodeURIComponent(c.id);

        // ✅ read nested user fields (matches your blade usage)
        const userName = c.user?.name ?? c.name ?? 'Deleted';
        const loginId = c.user?.login_id ?? c.login_id ?? '-';
        const passText = c.user?.password_text ?? c.password_text ?? '-';

        const branchCol = isAdmin
            ? `<td>${escHtml(c.created_by_branch?.branch_name ?? c.branch_name ?? 'Admin / Unknown')}</td>`
            : ``;

        const phone = c.phone ?? c.customer_phone ?? '-';
        const refBy = c.ref_by ?? '-';

        return `
            <tr>
                <td>
                    <a class="iconbtn" href="${testsUrl}" title="Orders / Tests">🧾</a>
                    <div class="small" style="margin-top:4px;">History</div>
                </td>

                <td>
                    <a class="iconbtn" href="${testHistoryUrl}" title="All Tests Table">🧪</a>
                    <div class="small" style="margin-top:4px;">History</div>
                </td>

                <td>
                    <div style="font-weight:900;color:#0f172a;">${escHtml(userName)}</div>
                    <div class="small">ID: ${escHtml(c.id)}</div>
                </td>

                <td>${escHtml(loginId)}</td>
                <td>${escHtml(passText)}</td>
                <td>${escHtml(phone)}</td>

                ${branchCol}

                <td>${statusBadgeHtml(!!c.is_active)}</td>
                <td>${escHtml(refBy)}</td>

                <td class="actions">
                    <a href="${editUrl}">Edit</a>

                    <form action="${deleteUrl}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${csrf}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button onclick="return confirm('Delete this customer? This will also delete their login user.')">Delete</button>
                    </form>
                </td>
            </tr>
        `;
    }

    let t = null;

    async function doSearch(){
        const q = (input.value || '').trim();

        if(!q){
            tbody.innerHTML = originalTbodyHTML;
            setHidden(clearBtn, true);
            setHidden(chip, true);
            setHidden(noMatch, true);
            setHidden(pagination, false);
            return;
        }

        setHidden(clearBtn, false);
        setHidden(pagination, true);

        try{
            const url = @json(route('customers.search')) + '?q=' + encodeURIComponent(q);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if(!res.ok) throw new Error('HTTP ' + res.status);

            const json = await res.json();
            const data = Array.isArray(json.data) ? json.data : [];

            chip.textContent = `${data.length} match${data.length === 1 ? '' : 'es'}`;
            setHidden(chip, false);

            if(data.length === 0){
                tbody.innerHTML = '';
                setHidden(noMatch, false);
                noMatch.textContent = 'No matching customers found.';
                return;
            }

            setHidden(noMatch, true);
            tbody.innerHTML = data.map(rowHtml).join('');
        } catch (e){
            tbody.innerHTML = '';
            setHidden(noMatch, false);
            noMatch.textContent = 'Search failed. Please try again.';
        }
    }

    function debounceSearch(){
        clearTimeout(t);
        t = setTimeout(doSearch, 250);
    }

    input.addEventListener('input', debounceSearch);

    clearBtn.addEventListener('click', function(){
        input.value = '';
        input.focus();
        doSearch();
    });

    input.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){
            input.value = '';
            doSearch();
        }
    });
})();
</script>
@endsection
