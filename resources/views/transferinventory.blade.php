@extends('user_navbar')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">


                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Transfer Mobiles</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
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
                                        <th>Sold at</th>
                                        <th>Added at</th>
                                        <th>Transfer To</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transferMobiles as $key)
    <tr>
        <td>{{ \Carbon\Carbon::parse($key->transfer_time)->tz('Asia/Karachi')->format('M d, Y, h:i A') }}</td>
        @if ($key->mobile) {{-- Add a null check --}}
            <td>{{ $key->mobile->mobile_name }}</td>
            <td>{{ $key->mobile->imei_number }}</td>
            <td>{{ $key->mobile->sim_lock }}</td>
            <td>{{ $key->mobile->color }}</td>
            <td>{{ $key->mobile->storage }}</td>
            <td>{{ $key->mobile->battery_health }}</td>
            <td>{{ $key->mobile->cost_price }}</td>
            <td>{{ $key->mobile->selling_price }}</td>
            <td>{{ $key->mobile->availability }}</td>
            <td>{{ $key->mobile->sold_at }}</td>
            <td>{{ $key->mobile->created_at }}</td>
        @else
             <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
                                                <td colspan="11">Mobile not available</td>
        @endif
        <td>{{ $key->toUser->name }}</td>
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
@endsection
