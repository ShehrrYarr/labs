@extends('user_navbar')
@section('content')

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
            <div class="content-body">

                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Deleted Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
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
                                        {{-- <th>Action</th> --}}

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobileData as $key)
                                        <tr>
                                           
                                            <!--<td>{{ \Carbon\Carbon::parse($key->created_at)->tz('Asia/Karachi')->format('M d, Y, h:i A') }}</td>-->
                                            <td>{{ \Carbon\Carbon::parse($key->created_at)->format(' Y-m-d / h:i ') }}</td>
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
                                            <td>{{ $key->battery_health }}</td>
                                            <td>{{ $key->cost_price }}</td>
                                            <td>{{ $key->selling_price }}</td>
                                            <td>{{ $key->availability }}</td>
                                            {{-- <td>
                                                <a href="" onclick="edit({{ $key->id }})"
                                                    data-toggle="modal" data-target="#exampleModal1">
                                                    <i class="feather icon-edit"></i></a> |
                                                    <a href=""  onclick="deletefn({{ $key->id }})"
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
    </div>
    <script>
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
    </script>
@endsection
