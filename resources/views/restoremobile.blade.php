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

                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Restore Mobiles</h4>


                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="soldTable">
                                <thead>
                                    <tr>
                                        <th>Restore at</th>
                                        <th>Restore By</th>
                                        <th>Mobile Name</th>
                                        <th>IMEI#</th>
                                        <th>Old Cost Price</th>
                                        <th>New Cost Price</th>
                                        <th>Old Selling Price</th>
                                        <th>New Selling Price</th>




                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($restoreMobiles as $key)
                                        <tr>
                                            <td>{{ $key->created_at }}</td>
                                            <td>{{ $key->restore_by }}</td>
                                            <td>{{ $key->mobile_name }}</td>
                                            <td>{{ $key->imei_number }}</td>
                                            <td>{{ $key->old_cost_price }}</td>
                                            <td>{{ $key->new_cost_price }}</td>
                                            <td>{{ $key->old_selling_price }}</td>
                                            <td>{{ $key->old_selling_price }}</td>


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

         //Genrate PDF

         document.getElementById("exportPDF").addEventListener("click", function() {
            var tableContent = document.getElementById("soldTable").outerHTML;
            var iframe = document.createElement("iframe");
            iframe.style.display = "none";
            document.body.appendChild(iframe);
            iframe.contentDocument.open();
            iframe.contentDocument.write(tableContent);
            iframe.contentDocument.close();

            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        });
        //end Genrate PDF
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
                    $('#mobile_name').val(data.result.mobile_name);
                    $('#imei_number').val(data.result.imei_number);
                    $('#sim_lock').val(data.result.sim_lock);
                    $('#color').val(data.result.color);
                    $('#storage').val(data.result.storage);
                    $('#battery_health').val(data.result.battery_health);
                    $('#customer_name').val(data.result.customer_name);
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

        // End Approve Function
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
                    $('#rimei_number').val(data.result.imei_number);
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

          //Owner Transfer
          function owner(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/findapmobile/' + id,
                success: function(data) {
                    $("#restoremobile").trigger("reset");

                    $('#oid').val(data.result.id);
                    $('#omobile_name').val(data.result.mobile_name);
                    $('#obattery_health').val(data.result.battery_health);
                    $('#ocost_price').val(data.result.cost_price);
                    $('#oselling_price').val(data.result.selling_price);

                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
        //Owner Transfer End



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
