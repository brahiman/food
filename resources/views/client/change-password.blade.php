@extends('client.dashboard')
@section('client')
    <script src="{{asset('backend/assets/libs/jquery/jquery.min.js')}}"></script>

    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Changer mot de passe</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Client</a></li>
                                <li class="breadcrumb-item active">Mot de passe</li>
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
                                                {{--<img src="{{(!empty($profilData->photo)) ? url('upload/client_img/'.$profilData->photo) : url('upload/default.jpg')}}" alt="image de profil" class="img-fluid rounded-circle d-block">--}}
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
                            <form method="POST" action="{{ route('client.changepwd.submit') }}">
                                @csrf
                                <!-- Mot de passe actuel -->
                                <div class="row mb-4 align-items-center">
                                    <label for="oldpassword" class="col-sm-3 col-form-label fw-semibold">Mot de passe
                                        actuel</label>
                                    <div class="col-sm-9">
                                        <input class="form-control shadow-sm @error('oldpwd') is-invalid @enderror"  type="password" name="oldpwd"
                                               id="oldpassword"
                                               placeholder="Entrez votre actuel mot de passe" >
                                        @error('oldpwd')
                                        <div class="text-danger mt-2" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nouveau mot de passe -->
                                <div class="row mb-4 align-items-center">
                                    <label for="newpassword" class="col-sm-3 col-form-label fw-semibold">Nouveau mot de
                                        passe</label>
                                    <div class="col-sm-9">
                                        <input class="form-control shadow-sm @error('new_password') is-invalid @enderror " type="password" name="new_password"
                                               id="newpassword"
                                               placeholder="Entrez votre nouveau mot de passe" required>
                                        @error('new_password')
                                        <div class="text-danger mt-2" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirmation du nouveau mot de passe -->
                                <div class="row mb-4 align-items-center">
                                    <label for="new_password_confirmation" class="col-sm-3 col-form-label fw-semibold">Répéter le
                                        mot de
                                        passe</label>
                                    <div class="col-sm-9">
                                        <input class="form-control shadow-sm  @error('new_password_confirmation') is-invalid @enderror" type="password" name="new_password_confirmation"
                                               id="confirmpassword"
                                               placeholder="Confirmez votre nouveau mot de passe" required>
                                        @error('new_password_confirmation')
                                        <div class="text-danger mt-2" role="alert">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Boutons -->
                                <div class="d-flex justify-content-between mt-5">
                                    <!-- Bouton Retour -->
                                    <button type="button"
                                            class="btn bg-primary text-white px-4 me-2"
                                            onclick="window.history.back();">
                                        <i class="mdi mdi-arrow-left"></i> Retour
                                    </button>

                                    <!-- Bouton Enregistrer -->
                                    <button type="submit"
                                            class="btn bg-success text-white px-4">
                                        <i class="mdi mdi-content-save"></i> Modifier le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>

        </div> <!-- container-fluid -->
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#image').change(function (e) {
                var file = e.target.files[0];
                var reader = new FileReader();

                // Cacher l'alerte si un fichier valide est sélectionné
                $('#alert-container').addClass('d-none');

                // Vérifier si le fichier est une image
                if (file && file.type.match('image.*')) {
                    reader.onload = function (e) {
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

{{--

@section('scripts')
    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        var message = {!! json_encode(Session::get('message')) !!};

        switch(type) {
            case 'info':
                toastr.info(message);
                break;
            case 'success':
                toastr.success(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'error':
                toastr.error(message);
                break;
        }
        @endif
    </script>
@endsection
--}}

