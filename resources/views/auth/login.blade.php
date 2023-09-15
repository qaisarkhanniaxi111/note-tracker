<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Login Page</title>

        @include('admin.includes.css')

    </head>

</head>

<body class="bg-light-gray" id="body">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh">
        <div class="d-flex flex-column justify-content-between">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-10">
                    <div class="card card-default mb-0">
                        <div class="card-header pb-0">
                            <div class="app-brand w-100 d-flex justify-content-center border-bottom-0">
                                <a class="w-auto pl-0" href="{{ route('site.home') }}">
                                    <img src="{{ asset('assets/general/img/logo.jpg') }}" alt="logo"
                                        style="width: 70px; border-radius: 50%">
                                </a>
                            </div>
                        </div>
                        <div class="card-body px-5 pb-5 pt-0">

                            <h4 class="text-dark mb-6 text-center">Sign in</h4>

                            <div class="form-group row {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }} ">
                                <div class="col-md-12" style="">
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="help-block">
                                            <strong class="text-danger">{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12 mb-4">
                                        <input type="email" name="email"
                                            class="form-control input-lg @error('email') is-invalid @enderror"
                                            id="email" value="{{ old('email') }}" placeholder="Email" required
                                            autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12 ">
                                        <input type="password" name="password"
                                            class="form-control input-lg @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="Password" required
                                            autocomplete="off">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">

                                        <div class="d-flex justify-content-between mb-3">

                                            <div class="custom-control custom-checkbox mr-3 mb-3">
                                                <input type="checkbox" class="custom-control-input" name="remember"
                                                    id="customCheck2" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customCheck2">Remember
                                                    me</label>
                                            </div>


                                            @if (Route::has('password.request'))
                                                <a class="text-color" href="{{ route('password.request') }}"> Forgot
                                                    password? </a>
                                            @endif

                                        </div>

                                         <!-- Google Recaptcha -->
                                         <div class="g-recaptcha mt-4" data-sitekey={{config('notetracker.recaptcha.key')}}></div><br>

                                        <button type="submit" class="btn btn-primary btn-pill mb-4">Sign In</button>


                                        <p>Don't have an account yet ?
                                            <a class="text-blue" href="{{ route('register') }}">Sign Up</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script async src="https://www.google.com/recaptcha/api.js">
    <script></script>
</body>
</html>
