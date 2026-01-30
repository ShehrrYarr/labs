@extends('admin_navbar')
@section('content')  
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
        <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-1">
                    <h3 class="content-header-title">Allocate Publications</h3>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item">Publication Allocation
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
                                    <h4 class="card-title" id="from-actions-top-left">Assign a Publication to a User</h4>
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
                                            <div class="alert alert-icon-right alert-success alert-dismissible mb-2" id="alert" role="alert">
                                                <span class="alert-icon"><i class="fa fa-info"></i></span>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <strong id="msg"></strong>
                                            </div>
                                        </div>
                                        <div class="table-responsive"> 
                                        <select name="publication" class="form-control" id="pub_id">
                                            @foreach($publications as $publication)
                                                <option value="{{$publication->id}}">{{$publication->name}}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        <select name="user" class="form-control" id="user_id">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        <button type="button" onclick="verifyAllocation()" class="btn btn-primary">
                                                <i class="fa fa-link"></i> Allocate
                                        </button>
                                        <br>
                                        <br>
                                        <table class="table table-striped table-bordered zero-configuration">
                                            <thead>
                                                <tr>
                                                    <td>User</td>
                                                    <td>Publication</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($user_publications as $key)
                                                    <tr>
                                                        <td>{{$key->name}}</td>
                                                        <td>
                                                            @foreach($key->publications as $publication)
                                                                {{$publication->name}} <a id="{{$publication->pivot->id}}" onclick="deleteAllocation(this.id)" href="#"><i style="color:red" class="fa fa-trash"></i></a><br>
                                                            @endforeach
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
        function deleteAllocation(pivot_id)
        {
            var request = XMLHttpRequest();
            request.open("get", "/unlinkpublication/"+pivot_id, false);
            request.send();
            console.log(request.response);
            location.reload();
        }
        function submitAllocation()
        {
            var pubId = document.getElementById("pub_id").value;
            var userId = document.getElementById("user_id").value;
            verifyAllocation(pubId, userId);


            const form = document.querySelector('#publication-allocation');
            form.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent the form from submitting in the traditional way
            // Get the form data
            const formData = new FormData(form);
            // Send a POST request to the Laravel backend with the form data
            axios.post('/storeallocation', formData)
                .then((response) => {
                    document.getElementById("msgbox").style.display = "block";
                    document.getElementById("msg").innerHTML = response.data;
                    
                    location.reload();
                })
                .catch((error) => {
                // Handle any errors that occur during the request
                console.error(error);
                });
            });
        }
        function verifyAllocation()
        {
            var pubId = document.getElementById("pub_id").value;
            var userId = document.getElementById("user_id").value; 
            var request = new XMLHttpRequest();
            request.open("get", "/verifyallocation/"+pubId+"/"+userId, false);
            request.send();
            console.log(request.response);
            if (request.response == "true") {
                document.getElementById("msgbox").style.display = "block";
                document.getElementById("alert").className = "alert alert-icon-right alert-danger alert-dismissible mb-2";
                document.getElementById("msg").innerHTML = "Already Allocated.";
            }
            else if(request.response == "false"){
                document.getElementById("msgbox").style.display = "block";
                document.getElementById("alert").className = "alert alert-icon-right alert-success alert-dismissible mb-2";
                document.getElementById("msg").innerHTML = "User Allocated.";
                window.setTimeout(function(){location.reload()},1000)
            }


        }
        
    </script>

@endsection