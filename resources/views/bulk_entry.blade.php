@extends('user_navbar')
@section('content')

    <div id="waitOverlay" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100vw; height:100vh; background:rgba(255,255,255,0.8); backdrop-filter: blur(3px); text-align:center;">
    <div style="position: absolute; top:45%; left:50%; transform: translate(-50%, -50%);">
        <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
          <span class="sr-only">Loading...</span>
        </div>
        <div style="margin-top: 20px; font-size: 1.3rem; color: #333;">
            Please wait while adding the mobiles...
        </div>
    </div>
</div>


    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success" id="successMessage">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('danger'))
                    <div class="alert alert-danger" id="dangerMessage" style="color: red;">
                        {{ session('danger') }}
                    </div>
                @endif

              
<div class="container">
    <h3>Bulk Add Mobiles</h3>
   <div class="card mb-3">
    <div class="card-body">
        <form id="bulk-add-form" onsubmit="return false;">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <select id="mobile_name_id" class="form-control" required>
                        <option value="">Mobile Name</option>
                        @foreach($mobileNames as $mobileName)
                            <option value="{{ $mobileName->id }}">{{ $mobileName->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="company_id" class="form-control" required>
                        <option value="">Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="group_id" class="form-control" required>
                        <option value="">Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="sim_lock" class="form-control" required>
                        <option value="">SIM Lock</option>
                        <option value="J.V">J.V</option>
                        <option value="PTA">PTA</option>
                        <option value="Non-PTA">Non-PTA</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" id="color" class="form-control" placeholder="Color" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" id="storage" class="form-control" placeholder="Storage" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" id="battery_health" class="form-control" placeholder="Battery Health">
                </div>
                <div class="col-md-3 mb-2">
                    <input type="number" step="0.01" id="cost_price" class="form-control" placeholder="Cost Price" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="number" step="0.01" id="selling_price" class="form-control" placeholder="Selling Price" required>
                </div>
                <!-- IMEI LAST, FULL WIDTH ON LG, 3 on XL -->
                <div class="col-md-7 mb-2">
                    <input type="text" id="imei_number" class="form-control" placeholder="IMEI Number" required autofocus>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="button" class="btn btn-success btn-block" id="addMobile">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>


    {{-- Table of added mobiles --}}
    <form id="bulkMobilesForm" method="POST" action="{{ route('mobiles.bulkStore') }}">
        @csrf
     
        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1 ">
                    <div class="card ">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Added Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table id="mobiles-table" class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                         <th>Mobile Name</th>
                    <th>Company</th>
                    <th>Group</th>
                    <th>IMEI Number</th>
                    <th>SIM Lock</th>
                    <th>Color</th>
                    <th>Storage</th>
                    <th>Battery Health</th>
                    <th>Cost Price</th>
                    <th>Selling Price</th>
                    <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                            </table>
                        </div>
                    </div>
                </div>
        <button type="submit" class="btn btn-primary" id="saveAllBtn">Save All</button>
    </form>
</div>
                



            </div>
        </div>
    </div>

<script>
    let index = 0;
    function addRowToTable() {
    let mobile_name_id = document.getElementById('mobile_name_id').value;
    let mobile_name_text = document.getElementById('mobile_name_id').selectedOptions[0].text;
    let company_id = document.getElementById('company_id').value;
    let company_text = document.getElementById('company_id').selectedOptions[0].text;
    let group_id = document.getElementById('group_id').value;
    let group_text = document.getElementById('group_id').selectedOptions[0].text;
    let imei_number = document.getElementById('imei_number').value;
    let sim_lock = document.getElementById('sim_lock').value;
    let color = document.getElementById('color').value;
    let storage = document.getElementById('storage').value;
    let battery_health = document.getElementById('battery_health').value;
    let cost_price = document.getElementById('cost_price').value;
    let selling_price = document.getElementById('selling_price').value;

    let row = `<tr>
        <td>
            <input type="hidden" name="mobiles[${index}][mobile_name_id]" value="${mobile_name_id}"/>
            ${mobile_name_text}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][company_id]" value="${company_id}"/>
            ${company_text}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][group_id]" value="${group_id}"/>
            ${group_text}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][imei_number]" value="${imei_number}"/>
            ${imei_number}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][sim_lock]" value="${sim_lock}"/>
            ${sim_lock}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][color]" value="${color}"/>
            ${color}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][storage]" value="${storage}"/>
            ${storage}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][battery_health]" value="${battery_health}"/>
            ${battery_health}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][cost_price]" value="${cost_price}"/>
            ${cost_price}
        </td>
        <td>
            <input type="hidden" name="mobiles[${index}][selling_price]" value="${selling_price}"/>
            ${selling_price}
        </td>
        <td><button type="button" class="btn btn-danger btn-sm removeRow">Remove</button></td>
    </tr>`;

    window.mobTable.row.add($(row)).draw();
    index++;

    // Reset inputs
    document.getElementById('mobile_name_id').selectedIndex = 0;
    document.getElementById('company_id').selectedIndex = 0;
    document.getElementById('group_id').selectedIndex = 0;
    document.getElementById('imei_number').value = '';
    document.getElementById('sim_lock').selectedIndex = 0;
    document.getElementById('color').value = '';
    document.getElementById('storage').value = '';
    document.getElementById('battery_health').value = '';
    document.getElementById('cost_price').value = '';
    document.getElementById('selling_price').value = '';
}

document.getElementById('addMobile').onclick = function() {
    // Collect values
    let mobile_name_id = document.getElementById('mobile_name_id').value;
    let company_id = document.getElementById('company_id').value;
    let group_id = document.getElementById('group_id').value;
    let imei_number = document.getElementById('imei_number').value;
    let sim_lock = document.getElementById('sim_lock').value;
    let color = document.getElementById('color').value;
    let storage = document.getElementById('storage').value;
    let cost_price = document.getElementById('cost_price').value;
    let selling_price = document.getElementById('selling_price').value;

    // Validation
    if(!mobile_name_id || !company_id || !group_id || !imei_number || !sim_lock || !color || !storage || !cost_price || !selling_price) {
        alert('Please fill all fields except Battery Health.');
        return;
    }

    // Duplicate check in DataTable
    let duplicate = false;
    let imeiColumnIndex = 3;
    window.mobTable.rows().every(function(rowIdx, tableLoop, rowLoop){
        let rowNode = $(this.node());
        let imeiInTable = rowNode.find('td').eq(imeiColumnIndex).find('input[type="hidden"]').val();
        if(imeiInTable === imei_number) {
            duplicate = true;
            return false;
        }
    });
    if(duplicate) {
        alert('Mobile with this IMEI is already added!');
        document.getElementById('imei_number').value = '';
        document.getElementById('imei_number').focus();
        return;
    }

    // AJAX check in DB
    $.get("{{ route('mobiles.checkImei') }}", { imei: imei_number }, function(data) {
        if (data.exists) {
            alert('Mobile with this IMEI is already added in database!');
            document.getElementById('imei_number').value = '';
            document.getElementById('imei_number').focus();
            return;
        } else {
            addRowToTable();
        }
    });
};


// Remove row event (delegated)
$('#mobiles-table tbody').on('click', '.removeRow', function() {
    window.mobTable.row($(this).parents('tr')).remove().draw();
});


$(document).ready(function() {
    window.mobTable = $('#mobiles-table').DataTable({
        // Optional DataTable configs
        searching: false,
        paging: true,
        info: true
    });
});

  $('#imei_number').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // prevent form submit/reload
            $('#addMobile').click();
        }
    });

   $('#bulkMobilesForm').on('submit', function(e) {
    // If there are no rows in the DataTable, prevent submit
    if(window.mobTable.rows().count() === 0) {
        alert('Please add at least one mobile before saving!');
        e.preventDefault();
        return false;
    }
    $('#waitOverlay').show();
});


</script>
  

@endsection