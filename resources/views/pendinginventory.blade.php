@extends('user_navbar')
@section('content')

 {{-- Edit For Pending Modal --}}

 <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
 aria-hidden="true">
 <div class="modal-dialog" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">Sold Mobile</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">
             <form class="form" id="soldmobile" action="{{ route('pendingRestore') }}" method="post"
                 enctype="multipart/form-data">
                 @csrf
                 @method('PUT')

                 <div class="form-body">

                     <div class="mb-1">
                         <label for="mobile_name" class="form-label">Mobile Name</label>
                         <input class="form-control" type="hidden" name="id" id="sid"
                             value="Update">
                         <input type="text" class="form-control" id="smobile_name" name="mobile_name" required
                             readonly>
                     </div>

                     <div class="mb-1" style="display: none">
                         <label for="imei_number" class="form-label">IMEI Number</label>
                         <input type="text" class="form-control" id="simei_number" name="imei_number"
                             required>
                     </div>

                     <div class="mb-1">
                         <label for="availability" class="form-label">Availability</label>
                         <select class="form-control" id="savailability" name="availability" required>
                             <option value="Available">Available</option>
                             <option value="Pending">Pending</option>
                         </select>
                     </div>



                     <div class="mb-1" style="display: none">
                         <label for="sim_lock" class="form-label">SIM Lock</label>
                         <select class="form-control" id="ssim_lock" name="sim_lock" required>
                             <option value="J.V">J.V</option>
                             <option value="PTA">PTA</option>
                             <option value="Non-PTA">Non-PTA</option>
                         </select>
                     </div>
                     <div class="mb-1" style="display: none">
                         <label for="is_approve" class="form-label">SIM Lock</label>
                         <select class="form-control" id="sis_approve" name="is_approve">
                             <option value="Approved">Approved</option>
                             <option selected value="Not_Approved">Not_Approved</option>

                         </select>
                     </div>

                     <div class="mb-1" style="display: none">
                         <label for="color" class="form-label">Color</label>
                         <input type="text" class="form-control" id="scolor" name="color" required>
                     </div>

                     <div class="mb-1" style="display: none">
                         <label for="storage" class="form-label">Storage</label>
                         <input type="text" class="form-control" id="sstorage" name="storage" required>
                     </div>
                     

                     <div class="mb-1">
                         <label for="cost_price" class="form-label">Cost Price</label>
                         <input type="number" class="form-control" id="scost_price" name="cost_price" required>
                     </div>

                     <div class="mb-1">
                         <label for="selling_price" class="form-label">Selling Price</label>
                         <input type="number" class="form-control" id="sselling_price" name="selling_price"
                             required>
                     </div>
                 </div>
                 <div class="form-actions">
                     <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                         <i class="feather icon-x"></i> Cancel
                     </button>
                     <button type="submit" class="btn btn-primary">
                         <i class="fa fa-check-square-o"></i> Save
                     </button>
                 </div>
             </form>
         </div>

     </div>
 </div>
</div>

{{-- End Pending For Sold Modal --}}


    {{-- Restore Modal --}}
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Restore Mobile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" id="restoremobile" action="{{ route('restoreMobile') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-body">

                            <div class="mb-1">
                                <label for="mobile_name" class="form-label">Mobile Name</label>
                                <input class="form-control" type="hidden" name="id" id="rid">
                                <input type="text" class="form-control" id="rmobile_name" name="mobile_name" disabled>
                            </div>

                          <div class="mb-1" style="display: none">
                                <label for="availability" class="form-label">Availability</label>
                                <select class="form-control" id="ravailability" name="availability" required>
                                    <option value="Available">Available</option>
                                    <option value="Sold">Sold</option>
                                </select>
                            </div>

                            <div class="mb-1" >
                                <label for="battery_health" class="form-label">Battery Health</label>
                                <input type="text" class="form-control" id="rbattery_health" name="battery_health" required>
                            </div>


                            <div class="mb-1" >
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input type="number" class="form-control" id="rcost_price" name="cost_price" required>
                            </div>

                            <div class="mb-1" >
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="rselling_price" name="selling_price" required>
                            </div>

                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                                <i class="feather icon-x"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-check-square-o"></i> Restore
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- End Restore Modal --}}

     {{-- Download Modal --}}
     <div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
     aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel2">Select Dates</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form class="form"  action="{{ route('mobiles.exportSold') }}" method="post"
                     enctype="multipart/form-data">
                     @csrf

                     <div class="form-body">

                         <div class="mb-1">
                             <label for="start_date" class="form-label">Start Date</label>


                             <input type="date" class="form-control" id="start_date" name="start_date">
                         </div>

                         <div class="mb-1">
                             <label for="end_date" class="form-label">End Date</label>


                             <input type="date" class="form-control" id="end_date" name="end_date">
                         </div>





                     </div>
                     <div class="form-actions">
                         <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                             <i class="feather icon-x"></i> Cancel
                         </button>
                         <button type="submit" class="btn btn-primary">
                             <i class="fa fa-check-square-o"></i> Download
                         </button>
                     </div>
                 </form>
             </div>

         </div>
     </div>
 </div>
 {{-- End Download Modal --}}



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
                            <h4 class="latest-update-heading-title text-bold-500">Pending Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>Pending at</th>
                                        <th>Mobile Name</th>
                                        <th>IMEI#</th>
                                        <th>SIM Lock</th>
                                        <th>Color</th>
                                        <th>Storage</th>
                                        <th>Battery Health</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Customer Name</th>
                                        <th>Availability</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobile as $key)
                                        <tr>
                                            <td>{{ $key->sold_at }}</td>
                                                     <td>
    @if(empty($key->mobile_name_id))
        {{ $key->mobile_name }}
    @elseif($key->mobileName)
        {{ $key->mobileName->name }}
    @else
        N/A
    @endif
</td>
                                            <td>{{ $key->imei_number }}</td>
                                            <td>{{ $key->sim_lock }}</td>
                                            <td>{{ $key->color }}</td>
                                            <td>{{ $key->storage }}</td>
                                            <td>{{ $key->battery_health}}</td>
                                            <td>{{ $key->cost_price }}</td>
                                            <td>{{ $key->selling_price }}</td>
                                            <td>{{ $key->customer_name }}</td>
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
        //  Edit for Pending Function
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

        // End Edit For Pending Function
        //  restore Function
        function restore(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/findapmobile/' + id,
                success: function(data) {
                    $("#restoremobile").trigger("reset");

                    $('#rid').val(data.result.id);
                    $('#rmobile_name').val(data.result.mobile_name);
                    $('#rbattery_health').val(data.result.battery_health);
                    $('#rcost_price').val(data.result.cost_price);
                    $('#rselling_price').val(data.result.selling_price);
                    $('#ravailability').val(Available);




                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Restore Function

 //Message Time Out
 setTimeout(function() {
            document.getElementById('dangerMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out


        //Message Time Out
        setTimeout(function() {
            document.getElementById('successMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out
    </script>
@endsection
