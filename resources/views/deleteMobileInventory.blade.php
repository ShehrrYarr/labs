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

                 

               

                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Deleted Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="mobileTable">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Added at</th>
                                        <th>Mobile Name</th>
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
                                        {{-- <th>Transfer</th> --}}
                                        {{-- <th>Action</th> --}}
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
                                            {{-- <td><a href="" onclick="transfer({{ $key->id }})"
                                                    data-toggle="modal" data-target="#exampleModal2">
                                                    <i class="fa fa-exchange" style="font-size: 20px"></i></a></td> --}}
                                            {{-- <td>
                                                <a href="" onclick="edit({{ $key->id }})"
                                                    data-toggle="modal" data-target="#exampleModal1">
                                                    <i class="feather icon-edit"></i></a> |
                                                <a href="" onclick="deletefn({{ $key->id }})"
                                                    data-toggle="modal" data-target="#exampleModal4"><i style="color:red"
                                                        class="feather icon-trash"></i></a>
                                            </td> --}}
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
    //Disable Mobile Button Function

        $(document).ready(function() {
        $('#storeMobile').on('submit', function() {
            // Change button text to "Saving..."
            $('#storeButton').html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        });
    });

        // End Disable Mobile  Button Function
        
        //Disable Mobile Sold Button Function

        $(document).ready(function() {
        $('#soldmobile').on('submit', function() {
            // Change button text to "Saving..."
            $('#soldButton').html('<i class="fa fa-spinner fa-spin"></i> Selling...').prop('disabled', true);
        });
    });

        // End Disable Mobile Sold Button Function
        
         //Disable Mobile Transfer Button Function

        $(document).ready(function() {
        $('#transferMobile').on('submit', function() {
            // Change button text to "Saving..."
            $('#transferButton').html('<i class="fa fa-spinner fa-spin"></i> Selling...').prop('disabled', true);
        });
    });

        // End Disable Mobile Teansfer Button Function
        
        //  Edit Function
        function edit(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editmobile/' + id,
                success: function(data) {
                   

                    $("#editmobile").trigger("reset");

                    $('#id').val(data.result.id);
if (data.result.mobile_name_id) {
    // For new entries, select the correct option in the dropdown
    $('#edit_mobile_name_id').val(data.result.mobile_name_id);
} else {
    // For old entries, add the plain name as a temporary option and select it
    let $select = $('#edit_mobile_name_id');
    // Remove any old temp option to avoid duplicates
    $select.find('option.temp-mobile-name').remove();
    // Add new temp option and select it
    $select.prepend('<option class="temp-mobile-name" value="">' + data.result.mobile_name + '</option>');
    $select.val('');
}

                    $('#imei_number').val(data.result.imei_number);
                    $('#sim_lock').val(data.result.sim_lock);
                    $('#color').val(data.result.color);
                    $('#battery_health').val(data.result.battery_health);
                    $('#storage').val(data.result.storage);
                    $('#cost_price').val(data.result.cost_price);
                    $('#availability').val(data.result.availability);
                    $('#selling_price').val(data.result.selling_price);


                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function

        //  Delete fn Function
        function deletefn(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editmobile/' + id,
                success: function(data) {
                    $("#deletemobile").trigger("reset");

                    $('#did').val(data.result.id);
                                                     $('#dmobile_name').val(data.result.mobile_name_display || '');




                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Delete Function



        //  Edit for Sold Function
        function sold(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editmobile/' + id,
                success: function(data) {
                    
                    $("#soldmobile").trigger("reset");

                    $('#sid').val(data.result.id);
                                  $('#smobile_name').val(data.result.mobile_name_display || '');

                    $('#simei_number').val(data.result.imei_number);
                    $('#ssim_lock').val(data.result.sim_lock);
                    $('#scolor').val(data.result.color);
                    $('#sstorage').val(data.result.storage);
                    $('#sbattery_health').val(data.result.battery_health);
                    $('#scost_price').val(data.result.cost_price);
                    $('#savailability').val(data.result.availability);
                    $('#sselling_price').val(data.result.selling_price);


                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit For Sold Function

        // Transfer Function
        function transfer(value) {
            console.log(value);

            var id = value;
            $.ajax({
                type: "GET",
                url: '/findmobile/' + id,
                success: function(data) {
                    $("#transfermobile").trigger("reset");

                    $('#tid').val(data.result.id);
                                                     $('#tmobile_name').val(data.result.mobile_name_display || '');

                    // console.log(data.result.mobile_name);


                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
        // End Transfer Function

        // End Sold Function

        //Message Time Out
        setTimeout(function() {
            document.getElementById('successMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out
        //Message Time Out
        setTimeout(function() {
            document.getElementById('dangerMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out

        // //Moment Library
        // $(document).ready(function() {
        //     // Initialize DataTable
        //     var table = $('.table').DataTable();

        //     // Update "Added at" column with formatted dates
        //     table.columns().every(function() {
        //         var column = this;
        //         if (column.header().textContent === 'Added at') {
        //             column.nodes().to$().each(function(cell, index) {
        //                 var originalDate = $(cell).text();
        //                 var formattedDate = moment(originalDate).format('MMMM Do YYYY, h:mm:ss a');
        //                 $(cell).text(formattedDate);
        //             });
        //         }
        //     });
        // });
    </script>
@endsection
