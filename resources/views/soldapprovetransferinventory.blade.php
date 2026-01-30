@extends('user_navbar')
@section('content')
    {{-- Approve Modal --}}
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Mobile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" id="approvemobile" action="{{ route('sellMobile') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-body">

                            <div class="mb-1">
                                <label for="mobile_name" class="form-label">Mobile Name</label>
                                <input class="form-control" type="hidden" name="id" id="id">
                                <input type="text" class="form-control" id="mobile_name" name="mobile_name" required
                                    readonly>
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="imei_number" class="form-label">IMEI Number</label>
                                <input type="text" class="form-control" id="imei_number" name="imei_number" required>
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="availability" class="form-label">Availability</label>
                                <select class="form-control" id="availability" name="availability" required>
                                    <option value="Available">Available</option>
                                    <option value="Sold">Sold</option>
                                </select>
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="sim_lock" class="form-label">SIM Lock</label>
                                <select class="form-control" id="sim_lock" name="sim_lock" required>
                                    <option value="J.V">J.V</option>
                                    <option value="PTA">PTA</option>
                                    <option value="Non-PTA">Non-PTA</option>
                                </select>
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color" required>
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="storage" class="form-label">Storage</label>
                                <input type="text" class="form-control" id="storage" name="storage" required>
                            </div>
                            <div class="mb-1" style="display: none;">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="mb-1" style="display: none;">
                                <label for="battery_health" class="form-label">Battery Health</label>
                                <input type="text" class="form-control" id="battery_health" name="battery_health"
                                    >
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input type="number" class="form-control" id="cost_price" name="cost_price" required>
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="selling_price" name="selling_price"
                                    required>
                            </div>
                            <div class="mb-1">
                                <label for="is_approve" class="form-label">Approve Status</label>
                                <select class="form-control" id="is_approve" name="is_approve" required>
                                    <option value="Approved">Approve</option>
                                    <option value="Not_Approved">Not Approve</option>
                                </select>
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
    {{-- End Approve Modal --}}

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
                                <input type="text" class="form-control" id="rbattery_health" name="battery_health" >
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


    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success" id="successMessage">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Sold Approved Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>Mobile Name</th>
                                        <th>IMEI#</th>
                                        <th>SIM Lock</th>
                                        <th>Color</th>
                                        <th>Storage</th>
                                        <th>Battery Health</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Availability</th>
                                        <th>Approve</th>
                                        <th>Customer Name</th>
                                        <th>Sold at</th>
                                        <th>Added at</th>
                                        <th>Transfer From</th>
                                        <th>Transfer Date</th>
                                        <th>Restore Purchase</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transferMobiles as $key)
                                        <tr>
                                                                       <td>
    @if(empty($key->mobile->mobile_name_id))
        {{ $key->mobile->mobile_name }}
    @elseif($key->mobile->mobileName)
        {{ $key->mobile->mobileName->name }}
    @else
        N/A
    @endif
</td>
                                            <td>{{ $key->mobile->imei_number }}</td>
                                            <td>{{ $key->mobile->sim_lock }}</td>
                                            <td>{{ $key->mobile->color }}</td>
                                            <td>{{ $key->mobile->storage }}</td>
                                            <td>{{ $key->mobile->battery_health }}</td>
                                            <td>{{ $key->mobile->cost_price }}</td>
                                            <td>{{ $key->mobile->selling_price }}</td>
                                            <td>
                                                <a href="#">

                                                    <span
                                                        class="badge badge-primary">{{ $key->mobile->availability }}</span>
                                                </a>
                                            </td>
                                            <td><a href="" onclick="approve({{ $key->mobile->id }})"
                                                    data-toggle="modal" data-target="#exampleModal1">
                                                    @if ($key->mobile->is_approve == 'Not_Approved')
                                                        <span
                                                            class="badge badge-danger">{{ $key->mobile->is_approve }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-success">{{ $key->mobile->is_approve }}</span>
                                                    @endif
                                                </a></td>
                                            <td>{{ $key->mobile->customer_name }}</td>
                                            <td>{{ $key->mobile->sold_at }}</td>
                                            <td>{{ $key->mobile->created_at }}</td>
                                            <td>{{ $key->fromUser->name }}</td>
                                            <td>{{ $key->transfer_time }}</td>
                                            <td><a href="" onclick="restore({{ $key->mobile->id }})"
                                                    data-toggle="modal" data-target="#exampleModal2">
                                                    <i class="fa fa-exchange" style="font-size: 20px"></i></a></td>


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
        //  approve Function
        function approve(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/findapmobile/' + id,
                success: function(data) {
                    $("#approvemobile").trigger("reset");

                    $('#id').val(data.result.id);
                                       $('#mobile_name').val(data.result.mobile_name_display || '');

                    $('#imei_number').val(data.result.imei_number);
                    $('#sim_lock').val(data.result.sim_lock);
                    $('#color').val(data.result.color);
                    $('#storage').val(data.result.storage);
                    $('#cost_price').val(data.result.cost_price);
                    $('#availability').val(data.result.availability);
                    $('#battery_health').val(data.result.battery_health);
                    $('#customer_name').val(data.result.customer_name);
                    $('#selling_price').val(data.result.selling_price);
                    $('#is_approve').val(data.result.is_approve);




                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function

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
                                        $('#rmobile_name').val(data.result.mobile_name_display || '');

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
            document.getElementById('successMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out
    </script>
@endsection
