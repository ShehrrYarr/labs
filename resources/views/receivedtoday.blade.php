@extends('user_navbar')
@section('content')
    {{-- Transfer Modal --}}
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Transfer Mobile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" id="transferMobile" action="{{ route('transferMobile') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-body">

                            <div class="mb-1">
                                <label for="mobile_name" class="form-label">Mobile Name</label>

                                <input class="form-control" type="hidden" name="mobile_id" id="tid" >
                                <input type="text" class="form-control" id="tmobile_name" name="mobile_name" readonly>
                            </div>



                            <div class="mb-1">
                                <label for="sim_lock" class="form-label">Transfer To</label>
                                <select class="form-control" id="to_user_id" name="to_user_id" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
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
    {{-- End Transfer Modal --}}
    {{-- Move to Inventory Modal --}}
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Transfer Mobile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" id="moveInventoryMobile" action="{{ route('moveToInventory') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-body">

                            <div class="mb-1">
                                <label for="mobile_name" class="form-label">Mobile Name</label>

                                <input class="form-control" type="hidden" name="mobile_id" id="mid" >
                                <input type="text" class="form-control" id="mmobile_name" name="mobile_name" readonly>
                            </div>



                            {{-- <div class="mb-1">
                                <label for="sim_lock" class="form-label">Transfer To</label>
                                <select class="form-control" id="to_user_id" name="to_user_id" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

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
    {{-- End Move To Inventory Modal --}}

    {{-- Sold Modal --}}

    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Availability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" id="soldmobile" action="{{ route('sellMobile') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-body">

                            <div class="mb-1">
                                <label for="mobile_name" class="form-label">Mobile Name</label>
                                <input class="form-control" type="hidden" name="id" id="id">
                                <input type="text" class="form-control" id="mobile_name" name="mobile_name" required readonly>
                            </div>

                            <div class="mb-1" style="display: none;">
                                <label for="imei_number" class="form-label">IMEI Number</label>
                                <input type="text" class="form-control" id="imei_number" name="imei_number" required>
                            </div>

                            <div class="mb-1">
                                <label for="availability" class="form-label">Availability</label>
                                <select class="form-control" id="availability" name="availability" required>
                                    <option value="Available">Available</option>
                                    <option value="Sold">Sold</option>
                                    <option value="Pending">Pending</option>

                                </select>
                            </div>

                            <div class="mb-1">
                                <label for="customer_name" class="form-label">Customer Name</label>

                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
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
                                <label for="battery_health" class="form-label">Battery Health</label>
                                <input type="text" class="form-control" id="battery_health" name="battery_health" >
                            </div>


                            <div class="mb-1" style="display: none;">
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input type="number" class="form-control" id="cost_price" name="cost_price" required>
                            </div>

                            <div class="mb-1">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="selling_price" name="selling_price"
                                    required>
                            </div>
                            <div class="mb-1" style="display: none;">
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

    {{-- End Sold Modal --}}
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

                    @if (session('danger'))
                    <div class="alert alert-danger" id="dangerMessage" style="color: red;">
                        {{ session('danger') }}
                    </div>
                @endif

                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Transfer Mobiles</h4>

                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration" id="transferTable">
                                    <thead>
                                        <tr>
                                            <th>Transfer Date</th>
                                            <th>Mobile Name</th>
                                            <th>IMEI#</th>
                                            <th>SIM Lock</th>
                                            <th>Color</th>
                                            <th>Storage</th>
                                            <th>Battery Health</th>
                                            <th>Cost Price</th>
                                            <th>Selling Price</th>
                                            <th>Availability</th>
                                            <th>Added at</th>
                                            <th>Transfer From</th>
                                            <th>Transfer</th>
                                            <th>Move To Inventory</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transferMobiles as $key)
                                            <tr>
                                                
                                                 <!--<td>{{ \Carbon\Carbon::parse($key->transfer_time)->tz('Asia/Karachi')->format('M d, Y, h:i A') }}</td>-->
                                                 <td>{{ \Carbon\Carbon::parse($key->transfer_time)->format(' Y-m-d / h:i ') }}</td>

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
                                                    <a href="" onclick="avail({{ $key->mobile->id }})"
                                                        data-toggle="modal" data-target="#exampleModal1">

                                                        <span
                                                            class="badge badge-primary">{{ $key->mobile->availability }}</span>
                                                    </a>
                                                </td>
                                                <td>{{ $key->mobile->created_at }}</td>
                                                <td>{{ $key->fromUser->name }}</td>
                                                <td><a href="" onclick="transfer({{ $key->mobile->id }})"
                                                        data-toggle="modal" data-target="#exampleModal2">
                                                        <i class="fa fa-exchange" style="font-size: 20px"></i></a></td>

                                                        <td><a href="" onclick="move({{ $key->mobile->id }})"
                                                        data-toggle="modal" data-target="#exampleModal3">
                                                        <i class="fa fa-exchange" style="font-size: 20px"></i></a></td>
                                                {{-- <td>
                                                    <a href="" onclick="edit({{ $key->id }})"
                                                        data-toggle="modal" data-target="#exampleModal1">
                                                        <i class="feather icon-edit"></i></a> |
                                                    <a href=""><i style="color:red"
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
     //Start dataTable

        $(document).ready(function() {
            $('#transferTable').DataTable({
                order: [
                    [0, 'desc']
                ]
            });
        });
        //End dataTable
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

        // Move to Inventory Function
        function move(value) {
            console.log(value);

            var id = value;
            $.ajax({
                type: "GET",
                url: '/findmobile/' + id,
                success: function(data) {
                    $("#moveInventoryMobile").trigger("reset");

                    $('#mid').val(data.result.id);
                     $('#mmobile_name').val(data.result.mobile_name_display || '');

                    // console.log(data.result.mobile_name);


                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
        // End Move to inventory Function

        //  Sold Function
        function avail(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/findapmobile/' + id,
                success: function(data) {
                    $("#soldmobile").trigger("reset");

                    $('#id').val(data.result.id);
                     $('#mobile_name').val(data.result.mobile_name_display || '');

                    $('#imei_number').val(data.result.imei_number);
                    $('#sim_lock').val(data.result.sim_lock);
                    $('#color').val(data.result.color);
                    $('#storage').val(data.result.storage);
                    $('#battery_health').val(data.result.battery_health);
                    // $('#customer_name').val(data.result.customer_name);
                    $('#cost_price').val(data.result.cost_price);
                    $('#availability').val(data.result.availability);
                    $('#selling_price').val(data.result.selling_price);
                    $('#is_approve').val(data.result.is_approve);




                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

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

    </script>
@endsection
