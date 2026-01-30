@extends('user_navbar')
@section('content')
   
    
 <style>
      
        .gradient-button3 {
            background: linear-gradient(to right, #74a8e0, #1779e2);
            border-color: #007bff;
            color: white;
        }
        .gradient-button4 {
            background: linear-gradient(to right, #e07974, #e21717);
            border-color: #ff0000;
            color: white;
        }
    </style>


    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
        

                  <div class="ml-1">
                <form method="GET" action="{{ route('allShopMobile') }}" class="mb-3 d-flex align-items-center">
                     <select class="form-control" id="nameSelect" name="mobile_name_id" required>
    <option value="">Select Mobile Name</option>
    @foreach($mobileNames as $mobileName)
        <option value="{{ $mobileName->id }}" {{ request('mobile_name_id') == $mobileName->id ? 'selected' : '' }}>{{ $mobileName->name }}</option>
    @endforeach
</select>
<select class="form-control" name="company_id">
    <option value="">Select Company</option>
    @foreach($companies as $company)
        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
    @endforeach
</select>
<select class="form-control" name="group_id" required>
    <option value="">Select Group</option>
    @foreach($groups as $group)
        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
    @endforeach
</select>
                    
                    <button type="submit" class="btn btn-primary mx-1">Filter</button>
                    <a href="{{ route('allShopMobile') }}" class="btn btn-secondary mx-1">Reset</a>
                </form>
            </div>

                <button type="button" class="btn btn-primary gradient-button3 ml-1" data-toggle="modal"
                    data-target="#exampleModal">
                    <i class="feather icon-smartphone" style="font-size: 20px;"></i>
                </button>
                <a href="/mobiles/bulk-entry" type="button" class="btn btn-primary gradient-button4 ml-1">
                <i class="feather icon-copy" style="font-size: 20px;"></i>
            </a>

                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Available Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="mobileTable">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Added at</th>
                                        <th>Mobile Name</th>
                                        <th>Owner Name</th>
                                        <th>Company</th>
                                        <th>Group</th>
                                        <th>IMEI#</th>
                                        <th>SIM Lock</th>
                                        <th>Color</th>
                                        <th>Storage</th>
                                        <th>Battery Health</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Availability</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobile as $key)
                                        <tr>
                                            {{-- <td>{{ $key->id }}</td> --}}
                                            {{-- <td>{{ $key->created_at }}</td> --}}
                                            <!--<td>{{ \Carbon\Carbon::parse($key->created_at)->tz('Asia/Karachi')->format('d h:i A, M ,Y') }}</td>-->
                                           <td>{{ \Carbon\Carbon::parse($key->created_at)->format(' Y-m-d / h:i ') }}</td>
                                           <!--<td>{{ \Carbon\Carbon::parse($key->created_at)->diffForHumans() }}</td>-->







                                          <td>
    @if(empty($key->mobile_name_id))
        {{ $key->mobile_name }}
    @elseif($key->mobileName)
        {{ $key->mobileName->name }}
    @else
        N/A
    @endif
</td>
<td>{{ $key->original_owner->name ?? 'N/A' }}</td>
                                           <td>{{ $key->company->name ?? 'N/A' }}</td>
<td>{{ $key->group->name ?? 'N/A' }}</td>

                                            <td>{{ $key->imei_number }}</td>
                                            <td>{{ $key->sim_lock }}</td>
                                            <td>{{ $key->color }}</td>
                                            <td>{{ $key->storage }}</td>
                                            <td>{{ $key->battery_health }}</td>
                                            <td>{{ $key->cost_price }}</td>
                                            <td>{{ $key->selling_price }}</td>
                                            <td>
                                                <a href="" onclick="sold({{ $key->id }})"
                                                    data-toggle="modal" data-target="#exampleModal3">
                                                    <span class="badge badge-success">{{ $key->availability }}</span>

                                                </a>

                                            </td>
                                            
                                           
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                 
                
              

            </div>
        </div>
    </div>
    <script>
             $(document).ready(function () {
            $('#nameSelect').select2({
                placeholder: "Select a Mobile Name",
                allowClear: true,
                width: '100%' // Optional to make it responsive
            });
        });
     //Start dataTable

        $(document).ready(function() {
            $('#mobileTable').DataTable({
                order: [
                    [0, 'desc']
                ]
            });
        });
        //End dataTable
   

    
@endsection
