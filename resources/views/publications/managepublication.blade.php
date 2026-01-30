@extends('admin_navbar')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-1">
                    <h3 class="content-header-title">Manage Publications</h3>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item">Manage
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
                                    <h4 class="card-title" id="from-actions-top-left">All Publications</h4>
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
                                                    <th>Publication Title</th>
                                                    <th>Publication Number</th>
                                                    <th>Description</th>
                                                    <th>File/Document</th>
                                                    <th>Students Limit</th>
                                                    <th>Price</th>
                                                    <th>Start</th>
                                                    <th>End</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($publications as $key)
                                                    <tr>
                                                        <td>{{ $key->name }}</td>
                                                        <td>{{ $key->publication_number }}</td>
                                                        <td>{{ $key->description }}</td>
                                                        <td><a
                                                                href="{{ route('downloadpublication', $key->id) }}">{{ $key->file_name }}</a>
                                                        </td>
                                                        <td>{{ $key->students }}</td>
                                                        <td>{{ $key->price }}</td>
                                                        <td>{{ $key->start_date }}</td>
                                                        <td>{{ $key->end_date }}</td>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Publication</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="publishUpdate" method="post" enctype="multipart/form-data">

                        <div class="container">
                            <div class="row">
                                <input class="form-control" type="hidden" name="id" id="id" value="Update">
                                <label for="name">Publication Title</label>
                                <input class="form-control" required type="text" name="publication_name"
                                    id="publication_name">
                            </div>
                            <div class="row">
                                <label for="name">Publication Number</label>
                                <input class="form-control" type="text" required name="publication_number"
                                    id="publication_number">
                            </div>
                            <div class="row">
                                <label for="name">Despription</label>
                                <input class="form-control" type="text" required name="description" id="description">
                            </div>
                            <div class="row">
                                <label for="name"> Update File/Document</label>
                                <input type="file" id="publication-file" class="form-control" name="publication_file"
                                    id="publication_file">
                            </div>
                            <div class="row">
                                <label for="name">Students Limit</label>
                                <input class="form-control" type="text" required name="std_limit" id="std_limit">
                            </div>
                            <div class="row">
                                <label for="name">Price</label>
                                <input class="form-control" type="number" required name="publication_price"
                                    id="publication_price">
                            </div>
                            <div class="row">
                                <label for="name">Start Date</label>
                                <input class="form-control" type="date" required name="start_date" id="start_date">
                            </div>
                            <div class="row">
                                <label for="name">End Date</label>
                                <input class="form-control" type="date" required name="end_date" id="end_date">
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
        //  Edit Function
        function edit(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editpublication/' + id,
                success: function(data) {
                    $("#publishUpdate").trigger("reset");
                    var base_url = '<?= url('public/publications') ?>';
                    $('#id').val(data.result.id);
                    $('#publication_name').val(data.result.name);
                    $('#description').val(data.result.description);
                    $('#publication_price').val(data.result.price);
                    $('#std_limit').val(data.result.students);
                    $('#start_date').val(data.result.start_date);
                    $('#end_date').val(data.result.end_date);
                    $('#publication_number').val(data.result.publication_number);


                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function

        // Update Function

        $('#publishUpdate').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/updatepublication',
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
    </script>
@endsection
