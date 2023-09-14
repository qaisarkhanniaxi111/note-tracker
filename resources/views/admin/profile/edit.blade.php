@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="content-wrapper">
    <div class="content"><!-- Card Profile -->
      <div class="row">
        <div class="col-xl-12">
          <!-- Account Settings -->

          <div class="card card-default">
            <div class="card-header">
              <h2 class="mb-5">Account Settings</h2>

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

              <form method="post" action="{{ route('admin.profile.update', $user ? $user->id: '') }}">
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
    </div>
  </div>
@endsection
