@extends('admin_navbar')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-1">
                    <h3 class="content-header-title">Create a Media</h3>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">Create
                            </li>

                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form actions layout section start -->
                <section id="form-action-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="from-actions-top-left">New Media</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="feather icon-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collpase show">
                                    <div class="card-body">
                                        <div class="card-text" id="msgbox" style="display:none">
                                            <div class="alert alert-icon-right alert-success alert-dismissible mb-2"
                                                role="alert">
                                                <span class="alert-icon"><i class="fa fa-info"></i></span>
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong id="msg"></strong>
                                            </div>
                                        </div>
                                        <form class="form" id="publication-form" method="post"
                                            enctype="multipart/form-data" action="{{ route('storemedia') }}">
                                            @csrf
                                            <div class="form-actions top">
                                                <button type="button" class="btn btn-warning mr-1">
                                                    <i class="feather icon-x"></i> Cancel
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-check-square-o"></i> Save
                                                </button>
                                            </div>
                                            <div class="form-body">

                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-2">
                                                        <label for="projectinput1">Media Agency Name</label>
                                                        <input type="text" required id="projectinput1"
                                                            class="form-control" placeholder="Name/Title"
                                                            name="media_agency_name">
                                                    </div>
                                                    <div class="form-group col-md-4 mb-2">
                                                        <label for="projectinput1">Category</label>
                                                        <select required id="media_category_id" name="media_category_id"
                                                            class="form-select form-control"
                                                            aria-label="Default select example">
                                                            <option value="" selected>Select Category</option>
                                                            @foreach ($mediaCategory as $sy)
                                                                <option value="{{ $sy->id }}">{{ $sy->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Form actions layout section end -->
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-1">
                    <h3 class="content-header-title">Manage Media</h3>
                </div>

            </div>
            <div class="content-body">
                <!-- Form actions layout section start -->
                <section id="form-action-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="from-actions-top-left">All Media</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="feather icon-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collpase show">
                                    <div class="card-body">
                                        <div class="card-text" id="msgbox" style="display:none">
                                            <div class="alert alert-icon-right alert-success alert-dismissible mb-2"
                                                role="alert">
                                                <span class="alert-icon"><i class="fa fa-info"></i></span>
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong id="msg"></strong>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Media Agency Name</th>
                                                    <th>Category</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mediaDetails as $key)
                                                    <tr>
                                                        <td>{{ $key->media_agency_name }}</td>
                                                        <td>{{ $key->category->category_name }}</td>
                                                        <td>
                                                            <a href="" onclick="edit({{ $key->id }})"
                                                                data-toggle="modal" data-target="#exampleModal">
                                                                <i class="feather icon-edit"></i></a> |
                                                            <a href="" onclick="deleteFnF({{ $key->id }})"><i
                                                                    style="color:red" class="feather icon-trash"></i></a>
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
                </section>
                <!-- // Form actions layout section end -->
            </div>
        </div>

    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>



    {{-- Modal --}}
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Media</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="mediaUpdate" method="post" enctype="multipart/form-data">

                        <div class="container">
                            <div class="row">
                                <input class="form-control" type="hidden" name="id" id="media_id"
                                    value="Update">
                                <label for="name">Media Agency Name</label>
                                <input class="form-control" type="text" name="media_agency_name"
                                    id="media_agency_name">
                            </div>
                            <div class="row">
                                <label for="name" class="pt-1">Category</label>
                                <select required id="media_category_id" name="media_category_id"
                                    class="form-select form-control" aria-label="Default select example">
                                    @foreach ($mediaCategory as $sy)
                                        <option value="{{ $sy->id }}">{{ $sy->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- End Modal --}}

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Edit Function
        function edit(value) {
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editmedia/' + id,
                success: function(data) {
                    $("#mediaUpdate").trigger("reset");
                    $('#media_id').val(data.result.id);
                    $('#media_agency_name').val(data.result.media_agency_name);
                    $('#media_category_id option[value="' + data.result.media_category_id + '"]').prop(
                        'selected', true);
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function

        // Update Function

        $('#mediaUpdate').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/updatemedia',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error:', errorThrown);
                }
            });
        });

        // Delete Function

        function deleteFnF(id) {
            $.ajax({
                type: "GET",
                url: '/deletemedia/' + id,
                success: location.reload

            });
        }
    </script>
@endsection
