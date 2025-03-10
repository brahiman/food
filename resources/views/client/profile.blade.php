@extends('client.dashboard')
@section('client')
    <script src="{{asset('backend/assets/libs/jquery/jquery.min.js')}}"></script>

    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Profil</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Client</a></li>
                                <li class="breadcrumb-item active">Profil</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm order-2 order-sm-1">
                                    <div class="d-flex align-items-start mt-3 mt-sm-0">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xl me-5">
                                                {{--<img src="{{(!empty($profilData->photo)) ? url('upload/admin_img/'.$profilData->photo) : url('upload/default.jpg')}}" alt="image de profil" class="img-fluid rounded-circle d-block">--}}
                                                <img
                                                    src="{{ (!empty($profilData->photo)) ? asset('upload/client_img/'.$profilData->photo) : asset('upload/default.jpg') }}"
                                                    alt="image de profil"
                                                    class="img-fluid rounded-circle d-block {{ !empty($profilData->photo) ? 'has-photo' : 'no-photo' }}">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div>
                                                <h5 class="font-size-16 mb-1">{{$profilData->name}}</h5>
                                                <p class="text-muted font-size-13">{{$profilData->phone}}</p>
                                                <div
                                                    class="d-flex flex-wrap align-items-start gap-2 gap-lg-3 text-muted font-size-13">
                                                    <div>
                                                        <i class="mdi mdi-circle-medium me-1 text-success align-middle"></i>{{$profilData->email}}
                                                    </div>
                                                    <div>
                                                        <i class="mdi mdi-circle-medium me-1 text-success align-middle"></i>{{$profilData->address}}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <form action="{{route('client.profile.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div>
                                            <div class="mb-3">
                                                <label for="example-text-input" class="form-label">Nom du restaurant</label>
                                                <input class="form-control" name="name" type="text" value="{{$profilData->name}}"
                                                       id="example-text-input">
                                            </div>
                                            <div class="mb-3">
                                                <label for="example-email-input" class="form-label">Email</label>
                                                <input class="form-control" type="email" name="email" value="{{$profilData->email}}"
                                                       id="example-email-input">
                                            </div>
                                            <div class="mb-3">
                                                <label for="example-tel-input" class="form-label">Telephone</label>
                                                <input class="form-control" type="tel" name="phone" value="{{$profilData->phone}}"
                                                       id="example-tel-input">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-3 mt-lg-0">
                                            <div class="mb-3">
                                                <label for="example-date-input" class="form-label">Adresse</label>
                                                <input class="form-control" type="text" name="address" value="{{$profilData->address}}"
                                                       id="example-date-input">
                                            </div>
                                            <div class="mb-3">
                                                <label for="example-month-input" class="form-label">Photo</label>
                                                <input class="form-control" type="file" name="photo" id="image">
                                            </div>
                                            <div class="mb-3">
                                                <img
                                                    src="{{ (!empty($profilData->photo)) ? asset('upload/client_img/'.$profilData->photo) : asset('upload/default.jpg') }}"
                                                    alt="image de profil" width="100px" id="showImage"
                                                    class="img-fluid rounded-circle p-1 bg-primary d-block {{ !empty($profilData->photo) ? 'has-photo' : 'no-photo' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="alert-container" class="alert alert-danger d-none" role="alert">
                                        Veuillez sélectionner un fichier image valide.
                                    </div>
                                    <div class="mb-4 text-end">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                            Enregistrer les Modifications
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>

        </div> <!-- container-fluid -->
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#image').change(function(e){
                var file = e.target.files[0];
                var reader = new FileReader();

                // Cacher l'alerte si un fichier valide est sélectionné
                $('#alert-container').addClass('d-none');

                // Vérifier si le fichier est une image
                if(file && file.type.match('image.*')){
                    reader.onload = function(e){
                        $('#showImage').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                } else {
                    // Afficher l'alerte si le fichier n'est pas une image
                    $('#alert-container').removeClass('d-none');
                }
            });
        });

    </script>

@endsection
