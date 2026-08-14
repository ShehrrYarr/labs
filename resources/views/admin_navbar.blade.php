<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Al Ghani Lab</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets') }}/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets') }}/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/extensions/unslider.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/weather-icons/climacons.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/fonts/meteocons/style.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/charts/morris.css">
    <!-- END: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/tables/datatable/datatables.min.css">
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/timeline.css">
    <!-- END: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/app-chat.css">
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns content-left-sidebar chat-application  fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-light bg-gradient-x-grey-blue">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="feather icon-menu font-large-1"></i></a></li>
                    <li class="nav-item">
                        <a class="navbar-brand" href="#">
                            <h5  class="brand-text">Al Ghani Lab</h5>
                        </a></li>
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="feather icon-menu"></i></a></li>

                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon feather icon-maximize"></i></a></li>

                    </ul>
                    <ul class="nav navbar-nav float-right">

                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="avatar avatar-online"><img src="https://eu.ui-avatars.com/api/?name={{Auth::user()->name}}&background=random" alt="avatar"><i></i></div><span class="user-name">{{Auth::user()->name}}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="user-profile.html"><i class="feather icon-user"></i> Edit Profile</a><a class="dropdown-item" href="app-email.html"><i class="feather icon-mail"></i> My Inbox</a><a class="dropdown-item" href="user-cards.html"><i class="feather icon-check-square"></i> Task</a><a class="dropdown-item" href="app-chat.html"><i class="feather icon-message-square"></i> Chats</a>
                                <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="feather icon-power"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            @if(Auth::check() && Auth::user()->category === 'staff')
                {{-- ═══════════════════════════════════════
                     STAFF SIDEBAR
                ════════════════════════════════════════ --}}
                <li class="navigation-header">
                    <span>Staff Panel</span>
                    <i class="feather icon-minus"></i>
                </li>

                <li class="nav-item @if(\Request::is('index')) active @endif">
                    <a href="{{ route('user.index') }}">
                        <i class="feather icon-home"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                @if(Auth::user()->hasStaffPermission('create_customers'))
                <li class="nav-item">
                    <a href="#">
                        <i class="feather icon-users"></i>
                        <span class="menu-title">Manage Customers</span>
                    </a>
                    <ul class="menu-content">
                        <li class="@if(\Request::is('customers')) active @endif">
                            <a class="menu-item" href="{{ route('customers.index') }}">Customers</a>
                        </li>
                        <li class="@if(\Request::is('customers/create')) active @endif">
                            <a class="menu-item" href="{{ route('customers.create') }}">Add Customer</a>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item @if(\Request::is('customers*')) active @endif">
                    <a href="{{ route('customers.index') }}">
                        <i class="feather icon-users"></i>
                        <span class="menu-title">Customers</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasStaffPermission('price_calculator'))
                <li class="nav-item">
                    <a href="#" onclick="openCalculator(); return false;">
                        <i class="feather icon-dollar-sign"></i>
                        <span class="menu-title">Price Calculator</span>
                    </a>
                </li>
                @endif

                @php
                    $hasSettings = Auth::user()->hasStaffPermission('manage_test_types')
                        || Auth::user()->hasStaffPermission('manage_test_categories')
                        || Auth::user()->hasStaffPermission('manage_lab_tests')
                        || Auth::user()->hasStaffPermission('manage_equipment');
                @endphp
                @if($hasSettings)
                <li class="nav-item">
                    <a href="#">
                        <i class="feather icon-settings"></i>
                        <span class="menu-title">Lab Settings</span>
                    </a>
                    <ul class="menu-content">
                        @if(Auth::user()->hasStaffPermission('manage_test_types'))
                        <li class="@if(\Request::is('test-types*')) active @endif">
                            <a class="menu-item" href="{{ route('test-types.index') }}">Test Types</a>
                        </li>
                        @endif
                        @if(Auth::user()->hasStaffPermission('manage_test_categories'))
                        <li class="@if(\Request::is('test-categories*')) active @endif">
                            <a class="menu-item" href="{{ route('test-categories.index') }}">Test Categories</a>
                        </li>
                        @endif
                        @if(Auth::user()->hasStaffPermission('manage_lab_tests'))
                        <li class="@if(\Request::is('lab-tests*')) active @endif">
                            <a class="menu-item" href="{{ route('lab-tests.index') }}">Lab Tests</a>
                        </li>
                        @endif
                        @if(Auth::user()->hasStaffPermission('manage_equipment'))
                        <li class="@if(\Request::is('equipment*')) active @endif">
                            <a class="menu-item" href="{{ route('equipment.index') }}">Equipment</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

            @else
                {{-- ═══════════════════════════════════════
                     ADMIN SIDEBAR
                ════════════════════════════════════════ --}}
                <li class=" navigation-header"><span>General</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="General"></i>
                </li>
                <li class=" nav-item @if(\Request::is('user.index')) active @endif"><a href="{{route('user.index')}}"><i class="feather icon-home"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a>
                </li>

                <li class=" nav-item"><a href="#"><i class="feather icon-book-open"></i><span class="menu-title" data-i18n="Templates">Manage Customers</span></a>
                    <ul class="menu-content">
                        <li class="@if (\Request::is('customers')) active @endif"><a class="menu-item"
                                href="{{ route('customers.index') }}" data-i18n="1 columns">Customers</a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a href="#"><i class="feather icon-book-open"></i><span class="menu-title" data-i18n="Templates">Manage Branches</span></a>
                    <ul class="menu-content">
                        <li class="@if (\Request::is('branches')) active @endif"><a class="menu-item"
                                href="{{ route('branches.index') }}" data-i18n="1 columns">Branches</a>
                        </li>
                    </ul>
                </li>

                <li class=" nav-item"><a href="#"><i class="feather icon-users"></i><span class="menu-title">Manage Staff</span></a>
                    <ul class="menu-content">
                        <li class="@if(\Request::is('staff')) active @endif">
                            <a class="menu-item" href="{{ route('staff.index') }}">All Staff</a>
                        </li>
                        <li class="@if(\Request::is('staff/create')) active @endif">
                            <a class="menu-item" href="{{ route('staff.create') }}">Add Staff</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" onclick="openCalculator(); return false;">
                        <i class="feather icon-dollar-sign"></i>
                        <span class="menu-title">Price Calculator</span>
                    </a>
                </li>

                <li class=" nav-item"><a href="#"><i class="feather icon-tv"></i><span class="menu-title" data-i18n="Templates">Settings</span></a>
                    <ul class="menu-content">
                        <li class="@if (\Request::is('test-types')) active @endif"><a class="menu-item"
                                href="{{ route('test-types.index') }}" data-i18n="1 columns">Test Types</a>
                        </li>
                        <li class="@if (\Request::is('equipment')) active @endif"><a class="menu-item"
                                href="{{ route('equipment.index') }}" data-i18n="1 columns">Equipments</a>
                        </li>
                        <li class="@if (\Request::is('test-categories')) active @endif"><a class="menu-item"
                                href="{{ route('test-categories.index') }}" data-i18n="1 columns">Test Categories</a>
                        </li>
                        <li class="@if (\Request::is('lab-tests')) active @endif"><a class="menu-item"
                                href="{{ route('lab-tests.index') }}" data-i18n="1 columns">Lab Tests</a>
                        </li>
                        <li class="@if (\Request::is('lab-settings')) active @endif"><a class="menu-item"
                                href="{{ route('lab-settings.edit') }}" data-i18n="1 columns">Lab Settings</a>
                        </li>
                    </ul>
                </li>
            @endif

            </ul>
        </div>
    </div>

    @yield('content')

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-dark navbar-border">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2023 <a class="text-bold-800 grey darken-2" href="https://www.linkedin.com/in/syed-shehryar-46a21a1b3/" target="_blank">Sherry </a></span><span class="float-md-right d-none d-lg-block">Hand-crafted & Made with <i class="feather icon-heart pink"></i></span></p>
    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets') }}/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/jquery.steps.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/daterange/daterangepicker.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/unslider-min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/timeline/horizontal-timeline.js"></script>
    <!-- END: Page Vendor JS-->
    <script src="{{ asset('app-assets') }}/vendors/js/tables/datatable/datatables.min.js"></script>
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets') }}/js/core/app-menu.js"></script>
    <script src="{{ asset('app-assets') }}/js/core/app.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/pages/app-chat.js"></script>
    <!-- END: Theme JS-->
    <script src="{{ asset('app-assets') }}/js/scripts/tables/datatables/datatable-basic.js"></script>
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets') }}/js/scripts/pages/dashboard-ecommerce.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/forms/wizard-steps.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- END: Page JS-->

@if(!Auth::check() || Auth::user()->category !== 'staff' || Auth::user()->hasStaffPermission('price_calculator'))
<!-- ═══════════════════════════════════════════
     PRICE CALCULATOR DRAWER
════════════════════════════════════════════ -->
<style>
#calcOverlay{position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:10000;display:none;}
#calcDrawer{
    position:fixed;top:0;right:-420px;width:400px;max-width:96vw;height:100vh;
    background:#fff;z-index:10001;box-shadow:-6px 0 30px rgba(0,0,0,.18);
    display:flex;flex-direction:column;transition:right .28s cubic-bezier(.4,0,.2,1);
}
#calcDrawer.open{right:0;}
#calcHead{
    display:flex;justify-content:space-between;align-items:center;
    padding:18px 20px 14px;border-bottom:1px solid #e5e7eb;background:#f8fafc;
}
#calcHead h3{margin:0;font-size:16px;font-weight:900;color:#0f172a;}
#calcClose{border:0;background:none;font-size:22px;cursor:pointer;color:#94a3b8;line-height:1;}
#calcSearch{
    margin:14px 16px 0;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;
    font-size:14px;outline:none;width:calc(100% - 32px);box-sizing:border-box;
}
#calcSearch:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12);}
#calcResults{
    max-height:220px;overflow-y:auto;margin:8px 16px 0;
    border:1px solid #e5e7eb;border-radius:10px;background:#f8fafc;
}
.calc-result-item{
    display:flex;justify-content:space-between;align-items:center;
    padding:9px 12px;border-bottom:1px solid #f1f5f9;cursor:pointer;font-size:13px;
}
.calc-result-item:last-child{border-bottom:none;}
.calc-result-item:hover{background:#eff6ff;}
.calc-result-name{font-weight:700;color:#0f172a;}
.calc-result-price{font-weight:900;color:#2563eb;white-space:nowrap;margin-left:8px;}
#calcList{flex:1;overflow-y:auto;padding:12px 16px;}
.calc-list-empty{color:#94a3b8;font-size:13px;text-align:center;padding:24px 0;}
.calc-row{
    display:flex;justify-content:space-between;align-items:center;
    padding:8px 10px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:6px;
    background:#fff;font-size:13px;
}
.calc-row-name{font-weight:700;color:#0f172a;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.calc-row-price{font-weight:900;color:#2563eb;margin:0 10px;white-space:nowrap;}
.calc-row-del{border:0;background:#fee2e2;color:#991b1b;border-radius:6px;padding:2px 8px;font-size:12px;cursor:pointer;font-weight:900;flex-shrink:0;}
#calcFooter{
    border-top:2px solid #e5e7eb;padding:14px 20px;background:#f8fafc;
}
#calcTotal{font-size:18px;font-weight:900;color:#0f172a;margin-bottom:10px;}
#calcTotal span{color:#2563eb;}
#calcClear{
    width:100%;padding:9px 0;border-radius:10px;border:1px solid #fca5a5;
    background:#fee2e2;color:#991b1b;font-size:13px;font-weight:900;cursor:pointer;
}
#calcNoResult{display:none;padding:10px 12px;font-size:13px;color:#64748b;}
</style>

<div id="calcOverlay" onclick="closeCalculator()"></div>
<div id="calcDrawer">
    <div id="calcHead">
        <h3>🧮 Price Calculator</h3>
        <button id="calcClose" onclick="closeCalculator()">✕</button>
    </div>

    <input id="calcSearch" type="text" placeholder="Search test type (e.g. CBC, Urine R/E)…" autocomplete="off">
    <div id="calcResults">
        <div id="calcNoResult" style="display:none;padding:10px 12px;font-size:13px;color:#64748b;">No matching test types.</div>
    </div>

    <div style="padding:10px 16px 0;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;">
        Added Tests
    </div>
    <div id="calcList">
        <div class="calc-list-empty" id="calcEmpty">No tests added yet. Search above to add.</div>
    </div>

    <div id="calcFooter">
        <div id="calcTotal">Total: <span id="calcTotalAmt">PKR 0.00</span></div>
        <button id="calcClear" onclick="calcClearAll()">Clear All</button>
    </div>
</div>

<script>
(function(){
    var _types    = null;   // cached test types
    var _added    = [];     // [{id, name, price}]
    var _fetching = false;

    window.openCalculator = function() {
        document.getElementById('calcOverlay').style.display = 'block';
        document.getElementById('calcDrawer').classList.add('open');
        if (!_types && !_fetching) fetchTypes();
        document.getElementById('calcSearch').focus();
    };

    window.closeCalculator = function() {
        document.getElementById('calcOverlay').style.display = 'none';
        document.getElementById('calcDrawer').classList.remove('open');
    };

    function fetchTypes() {
        _fetching = true;
        fetch('{{ route("calculator.types") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            _types    = data;
            _fetching = false;
            renderResults(document.getElementById('calcSearch').value);
        });
    }

    function money(n){ return 'PKR ' + Number(n||0).toLocaleString('en-PK', {minimumFractionDigits:2, maximumFractionDigits:2}); }

    function renderResults(q) {
        var container = document.getElementById('calcResults');
        var noResult  = document.getElementById('calcNoResult');
        container.innerHTML = '';
        container.appendChild(noResult);

        if (!q || !_types) { noResult.style.display = 'none'; return; }

        q = q.trim().toLowerCase();
        var matches = _types.filter(function(t){
            return t.name.toLowerCase().includes(q) ||
                   (t.code && t.code.toLowerCase().includes(q));
        });

        noResult.style.display = matches.length ? 'none' : 'block';

        matches.forEach(function(t){
            var row = document.createElement('div');
            row.className = 'calc-result-item';
            row.innerHTML =
                '<span class="calc-result-name">' + esc(t.name) + '</span>' +
                '<span class="calc-result-price">' + money(t.price) + '</span>';
            row.addEventListener('click', function(){ addTest(t); });
            container.appendChild(row);
        });
    }

    function addTest(t) {
        // Prevent duplicate
        if (_added.find(function(a){ return a.id === t.id; })) return;
        _added.push({id: t.id, name: t.name, price: parseFloat(t.price || 0)});
        renderList();
        document.getElementById('calcSearch').value = '';
        renderResults('');
    }

    function removeTest(id) {
        _added = _added.filter(function(a){ return a.id !== id; });
        renderList();
    }

    window.calcClearAll = function() {
        _added = [];
        renderList();
    };

    function renderList() {
        var list  = document.getElementById('calcList');
        var empty = document.getElementById('calcEmpty');

        // Remove old rows but keep empty message
        Array.from(list.querySelectorAll('.calc-row')).forEach(function(el){ el.remove(); });

        if (_added.length === 0) {
            empty.style.display = 'block';
        } else {
            empty.style.display = 'none';
            _added.forEach(function(a){
                var row = document.createElement('div');
                row.className = 'calc-row';
                row.innerHTML =
                    '<span class="calc-row-name" title="' + esc(a.name) + '">' + esc(a.name) + '</span>' +
                    '<span class="calc-row-price">' + money(a.price) + '</span>' +
                    '<button class="calc-row-del" onclick="removeTestById(' + a.id + ')">✕</button>';
                list.appendChild(row);
            });
        }

        var total = _added.reduce(function(s,a){ return s + a.price; }, 0);
        document.getElementById('calcTotalAmt').textContent = money(total);
    }

    window.removeTestById = function(id){ removeTest(id); };

    function esc(s){
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    document.getElementById('calcSearch').addEventListener('input', function(){
        renderResults(this.value);
    });
})();
</script>
@endif

</body>
<!-- END: Body-->

</html>
