@extends('branches.branch_navbar')
@section('content')

    <style>
    .card {
        border-radius: 12px;
      
    }
  
</style>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
               

             

                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1 ">
                    <div class="card ">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Available Test Types</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                     <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Code</th>
                <th>Status</th>
            </tr>
                                </thead>
                              <tbody>
            @forelse($testTypes as $type)
                <tr>
                    <td>{{ $type->id }}</td>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->price }}</td>
                    <td>{{ $type->code ?? 'N/A' }}</td>
                    <td>
                        @if($type->is_active)
                            <span class="badge active">Active</span>
                        @else
                            <span class="badge inactive">Inactive</span>
                        @endif
                    </td>
                    
                </tr>
            @empty
                <tr><td colspan="4">No test types found.</td></tr>
            @endforelse
        </tbody>
                            </table>
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
                url: '/editcompany/' + id,
                success: function(data) {
                    $("#editmobile").trigger("reset");

                    $('#id').val(data.result.id);
                    $('#vname').val(data.result.name);
                  
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function

           //  Delete Function
        function remove(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editcompany/' + id,
                success: function(data) {
                    $("#deleteMobile").trigger("reset");

                    $('#did').val(data.result.id);
                    $('#dname').val(data.result.name);
                   
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function
    </script>

@endsection