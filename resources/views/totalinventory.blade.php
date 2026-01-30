@extends('user_navbar')
@section('content')
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
                            <h4 class="latest-update-heading-title text-bold-500">Total Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="totalTable">
                                <thead>
                                    <tr>
                                        <th>Added at</th>
                                        <th>Mobile Name</th>
                                        <th>IMEI#</th>
                                        <th>SIM Lock</th>
                                        <th>Color</th>
                                        <th>Storage</th>
                                        <th>Battery Health</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Availability</th>
                                        <th>Transfer to</th>
                                        <th>Received Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result as $item)
                                            <tr>
                                                <!--<td>{{ \Carbon\Carbon::parse($item->created_at)->tz('Asia/Karachi')->format('M d, Y, h:i A') }}-->
                                                <!--</td>-->
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format(' Y-m-d / h:i ') }}</td>
                                                                           <td>
    @if(empty($item->mobile_name_id))
        {{ $item->mobile_name }}
    @elseif($item->mobileName)
        {{ $item->mobileName->name }}
    @else
        N/A
    @endif
</td>
                                                <td>{{ $item->imei_number }}</td>
                                                <td>{{ $item->sim_lock }}</td>
                                                <td>{{ $item->color }}</td>
                                                <td>{{ $item->storage }}</td>
                                                <td>{{ $item->battery_health }}</td>
                                                <td>{{ $item->cost_price }}</td>
                                                <td>{{ $item->selling_price }}</td>
                                                <td>
                                                    @if ($item->availability == 'Available')

                                                            <span class="badge badge-primary">{{ $item->availability }}</span>
                                                            @else
                                                            <span class="badge badge-danger">{{ $item->availability }}</span>
                                                    @endif
                                                </td>

                                                <td>N/A</td>
                                                <td>N/A</td>

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
            $('#totalTable').DataTable({
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
                    $('#tmobile_name').val(data.result.mobile_name);
                    // console.log(data.result.mobile_name);


                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
        // End Transfer Function

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
