@extends('admin_navbar')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-1">
                    <h3 class="content-header-title">Create a Publication</h3>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
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
                                    <h4 class="card-title" id="from-actions-top-left">New Publication</h4>
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
                                            <div class="alert alert-icon-right alert-success alert-dismissible mb-2" role="alert">
                                                <span class="alert-icon"><i class="fa fa-info"></i></span>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong id="msg"></strong>
                                            </div>
                                        </div>
                                        <form class="form" id="publication-form" method="post" enctype="multipart/form-data">
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
                                                        <label for="projectinput1">Publication Name/Title</label>
                                                        <input type="text" required id="projectinput1" class="form-control" placeholder="Name/Title" name="publication_name">
                                                    </div>
                                                    <div class="form-group col-md-3 mb-2">
                                                        <label for="projectinput1">Publication Number</label>
                                                        <input type="text" required id="" class="form-control" placeholder="e.g. Pub-1" name="publication_number">
                                                    </div>
                                                    <div class="form-group col-md-3 mb-2">
                                                        <label for="projectinput2">File/Attachment</label>
                                                        <input type="file" required id="publication-file" class="form-control" name="publication_file">
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="form-group col-12 mb-2">
                                                        <label for="projectinput9">Description/Details</label>
                                                        <textarea id="" required rows="5" class="form-control" name="description" placeholder="About Project"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-2">
                                                        <label for="projectinput1">Students Limit</label>
                                                        <input type="number" required id="projectinput1" class="form-control" placeholder="Students Limit" name="std_limit">
                                                    </div>
                                                    <div class="form-group col-md-6 mb-2">
                                                        <label for="projectinput2">Price</label>
                                                        <input type="number" required id="projectinput2" class="form-control" placeholder="Publication Price" name="publication_price">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 mb-2">
                                                        <label for="projectinput1">Start Date</label>
                                                        <input type="date" id="issueinput3" class="form-control" name="start_date" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Date Opened">
                                                    </div>
                                                    <div class="form-group col-md-6 mb-2">
                                                        <label for="projectinput2">End Date</label>
                                                        <input type="date" id="issueinput3" class="form-control" name="end_date" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Date Closed">
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

    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const form = document.querySelector('#publication-form');
        form.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent the form from submitting in the traditional way
        // Get the form data
        const formData = new FormData(form);
        // Add the file to the form data
        const fileInput = document.querySelector('#publication-file');
        formData.append('file', fileInput.files[0]);

        // Send a POST request to the Laravel backend with the form data
        axios.post('/storepublication', formData)
            .then((response) => {
                document.getElementById("msgbox").style.display = "block";
                document.getElementById("msg").innerHTML = response.data;
                form.reset();
            })
            .catch((error) => {
            // Handle any errors that occur during the request
            console.error(error);
            });
        });


    </script>

@endsection
