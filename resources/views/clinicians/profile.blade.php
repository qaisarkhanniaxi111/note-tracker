@extends('layouts.clinician')

@section('title', 'Edit Profile')

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- notification css cdn  -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/plugins/toaster/toastr.min.css') }}">
@endsection

@section('content')

    <section class="py-8">
        <div class="px-4 pb-10 pt-10 mb-6 bg-white rounded shadow">

            <div class="container" style="margin-bottom: 30px">
                <div class="card card-default">
                    <div class="card-header">
                      <h5>Account Settings</h5>

                    </div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                      <form method="post" action="{{ route('clinician.profile.update', $user->id) }}">
                        @csrf
                        <div class="row mb-2">
                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="firstName">Name</label>
                              <input type="text" name="name" value="{{ old('email', $user ? $user->name: '') }}" class="form-control" id="firstName" >
                            </div>
                          </div>

                          <div class="col-lg-6">
                            <div class="form-group">
                              <label for="lastName">Email</label>
                              <input type="email" name="email" value="{{ old('email', $user ? $user->email: '') }}" class="form-control" id="lastName">
                            </div>
                          </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="newPassword">Old password</label>
                            <input type="password" name="old_password" class="form-control" id="old_password" placeholder="Enter old password" autocomplete="off">
                        </div>

                        <div class="form-group mb-4">
                          <label for="newPassword">New password</label>
                          <input type="password" name="password" class="form-control" id="newPassword" placeholder="Enter new password" autocomplete="off">
                        </div>

                        <div class="form-group mb-4">
                          <label for="conPassword">Confirm password</label>
                          <input type="password" name="password_confirmation" class="form-control" id="conPassword" placeholder="Enter confirmed password" autocomplete="off">
                        </div>

                        <div class="d-flex justify-content-end mt-6">
                          <button type="submit" class="btn btn-primary mb-2 btn-pill">Update Profile</button>
                        </div>

                      </form>
                    </div>
                  </div>
            </div>


        </div>
    </section>
@endsection

@section('scripts')
<script src="{{ asset('assets/admin/plugins/toaster/toastr.min.js') }}"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

<script>

    @if(Session::has('alert-success'))
        toastr["success"]("{{ Session::get('alert-success') }}");
    @endif

    @if(Session::has('alert-info'))
        toastr["info"]("{{ Session::get('alert-info') }}");
    @endif

    @if(Session::has('alert-danger'))
        toastr["error"]("{{ Session::get('alert-danger') }}");
    @endif

</script>
@endsection
