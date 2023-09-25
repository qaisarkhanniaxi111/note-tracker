<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/clinician/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clinician/css/style.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="shuffle-for-bootstrap.png">
    @yield('css')
</head>

<body>
    <div class="">

        <section>
            <nav class="navbar navbar-expand-xl navbar-light bg-white flex-wrap">
                <div class="container-fluid">
                    <div class="d-flex w-100 align-items-center">
                        <a class="navbar-brand" href="{{ route('clinician.dashboard') }}">
                            <img class="img-fluid imga" src="{{ asset('assets/general/img/logo.png') }}"
                                alt="" width="auto" style="width: 150px !important"></a>
                        <button class="navbar-burger navbar-toggler bg-primary-light ms-auto" type="button">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse ms-12">
                            <ul class="navbar-nav">

                                <li class="nav-item {{ request()->is('clinician/dashboard') ? 'active': '' }}" id="usersmanagement">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('clinician.dashboard') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewbox="0 0 24 24" class="me-2 text-secondary">
                                            <path
                                                d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm13-2h-2v3h-3v2h3v3h2v-3h3v-2h-3z">
                                            </path>
                                        </svg><span class="small">
                                            <div id="dashboard">Dashboard</div>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->is('clinician/profile/edit') ? 'active': '' }}" id="profile">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('clinician.profile.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewbox="0 0 24 24" class="me-2 text-secondary">
                                            <path
                                                d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm13-2h-2v3h-3v2h3v3h2v-3h3v-2h-3z">
                                            </path>
                                        </svg><span class="small">
                                            <div id="profile">Update Profile</div>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item" id="logout">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('logout') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            aria-hidden="true" class="me-2 text-secondary">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg><span class="small">
                                            <div style="color:red">Logout</div>
                                        </span>
                                    </a>
                                </li>


                            </ul>
                            <ul class="navbar-nav me-6 ms-auto"></ul>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="position-relative navbar-menu d-none" style="z-index: 9999;">
                <div class="navbar-backdrop position-fixed top-0 end-0 bottom-0 start-0 bg-dark" style="opacity: .5">
                </div>
                <div class="position-fixed top-0 start-0 bottom-0 w-75 mw-sm-xs pt-6 bg-white overflow-auto">
                    <div class="px-6 pb-6 position-relative border-bottom border-secondary-light">
                        <div class="d-inline-flex align-items-center">
                            <a href="#">
                                {{-- <img class="img-fluid" src="artemis-assets/logos/artemis-logo-light.svg" alt=""
                                    width="auto"></a> --}}
                        </div>
                    </div>
                    <div class="py-6 px-6">
                        <div>
                            <h3 class="mb-2 text-secondary text-uppercase small">Main</h3>
                            <ul class="nav flex-column mb-8">

                                <li class="nav-item" id="usersmanagement_main">
                                    <a class="nav-link d-flex align-items-center" href="usersmanagement">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewbox="0 0 24 24" class="me-2 text-secondary">
                                            <path
                                                d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm13-2h-2v3h-3v2h3v3h2v-3h3v-2h-3z">
                                            </path>
                                        </svg><span class="small">
                                            <div id="usersmanagement_main_a">Users Management</div>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item" id="fillsurveys_main">
                                    <a class="nav-link d-flex align-items-center" href="fillsurveys">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewbox="0 0 24 24" class="me-2 text-secondary">
                                            <path
                                                d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm13-2h-2v3h-3v2h3v3h2v-3h3v-2h-3z">
                                            </path>
                                        </svg><span class="small">
                                            <div id="fillsurveys_main_a">Fill Pending Surveys</div>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item" id="userchangepassword_main">
                                    <a class="nav-link d-flex align-items-center" href="userchangepassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewbox="0 0 24 24" class="me-2 text-secondary">
                                            <path
                                                d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm13-2h-2v3h-3v2h3v3h2v-3h3v-2h-3z">
                                            </path>
                                        </svg><span class="small">
                                            <div id="userchangepassword_a">Change Password</div>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item nav_pills" id="logout">
                                    <a class="nav-link d-flex align-items-center" href="logout_user">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" aria-hidden="true" class="me-2 text-secondary">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg><span class="small">
                                            <div style="color:red">Logout</div>
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        @yield('content')

        <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
        <script src="{{ asset('assets/clinician/js/bootstrap.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/clinician/js/app.js') }}"></script> --}}

        @yield('scripts')
        <script src="{{ asset('assets/clinician/js/main.js') }}"></script>
</body>

</html>
