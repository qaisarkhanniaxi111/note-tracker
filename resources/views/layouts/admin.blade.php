<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title') | {{ config('app.name') }}</title>
	@include('admin.includes.css')
    @yield('css')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
	<script>
	// Show the progress bar
	NProgress.start();
	// Increase randomly
	var interval = setInterval(function() {
		NProgress.inc();
	}, 1000);
	// Trigger finish when page fully loaded
	$(window).load(function() {
		clearInterval(interval);
		NProgress.done();
	});
	// Trigger bar when exiting the page
	$(window).unload(function() {
		NProgress.start();
	});
	</script>
	<div class="wrapper">
		<!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
		<aside class="left-sidebar sidebar-dark" id="left-sidebar">
			<div id="sidebar" class="sidebar sidebar-with-footer">
				<!-- Aplication Brand -->
				<div class="app-brand">
					<a href="/index.html"> <img src="{{ asset('assets/admin/images/logo.png') }}" style="width: 50px; background: #9e6de0; border-radius: 50%" alt="CDL"> <span class="brand-name"><h2 class="text-white"><small>{{ config('app.name') }}</small></h2></span> </a>
				</div>
				<!-- begin sidebar scrollbar -->
				<div class="sidebar-left" data-simplebar style="height: 100%;">
					<!-- sidebar menu -->
					<ul class="nav sidebar-inner" id="sidebar-menu">
						<li class="{{ request()->is('admin/dashboard') ? 'active': '' }}">
							<a class="sidenav-item-link" href="{{ route('admin.dashboard') }}"> <i class="mdi mdi-briefcase-account-outline"></i> <span class="nav-text">Dashboard</span> </a>
						</li>

						<li class="section-title"> Apps </li>

                        <li class="{{ request()->is('admin/locations') ? 'active': '' }}">
							<a class="sidenav-item-link" href="{{ route('admin.locations.index') }}"> <i class="mdi mdi-map-marker"></i> <span class="nav-text">Locations</span> </a>
						</li>

                        <li class="{{ request()->is('admin/clinicians') ? 'active': '' }}">
							<a class="sidenav-item-link" href="{{ route('admin.clinicians.index') }}"> <i class="mdi mdi-account"></i> <span class="nav-text">Clinicians</span> </a>
						</li>

                        <li class="{{ request()->is('admin/error_types') ? 'active': '' }}">
							<a class="sidenav-item-link" href="{{ route('admin.error_types.index') }}"> <i class="mdi mdi-map-marker"></i> <span class="nav-text">Error Types</span> </a>
						</li>

                        <li class="{{ request()->is('admin/notes') ? 'active': '' }}">
							<a class="sidenav-item-link" href="{{ route('admin.notes.index') }}"> <i class="mdi mdi-map-marker"></i> <span class="nav-text">Notes</span> </a>
						</li>

						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#fruit" aria-expanded="false" aria-controls="fruit"> <i class="mdi mdi-image-filter-none"></i> <span class="nav-text">Fruits</span> <b class="caret"></b> </a>
							<ul class="collapse" id="fruit" data-parent="#sidebar-menu">
								<div class="sub-menu">
									<li>
										<a class="sidenav-item-link" href=""> <span class="nav-text">Create</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href=""> <span class="nav-text">Index</span> </a>
									</li>
								</div>
							</ul>
						</li>



						<li class="section-title"> UI Elements </li>
						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#ui-elements" aria-expanded="false" aria-controls="ui-elements"> <i class="mdi mdi-folder-outline"></i> <span class="nav-text">UI Components</span> <b class="caret"></b> </a>
							<ul class="collapse" id="ui-elements" data-parent="#sidebar-menu">
								<div class="sub-menu">
									<li>
										<a class="sidenav-item-link" href="alert.html"> <span class="nav-text">Alert</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="badge.html"> <span class="nav-text">Badge</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="breadcrumb.html"> <span class="nav-text">Breadcrumb</span> </a>
									</li>
									<li class="has-sub">
										<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#buttons" aria-expanded="false" aria-controls="buttons"> <span class="nav-text">Buttons</span> <b class="caret"></b> </a>
										<ul class="collapse" id="buttons">
											<div class="sub-menu">
												<li> <a href="button-default.html">Button Default</a> </li>
												<li> <a href="button-dropdown.html">Button Dropdown</a> </li>
												<li> <a href="button-group.html">Button Group</a> </li>
												<li> <a href="button-social.html">Button Social</a> </li>
												<li> <a href="button-loading.html">Button Loading</a> </li>
											</div>
										</ul>
									</li>
									<li>
										<a class="sidenav-item-link" href="card.html"> <span class="nav-text">Card</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="carousel.html"> <span class="nav-text">Carousel</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="collapse.html"> <span class="nav-text">Collapse</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="editor.html"> <span class="nav-text">Editor</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="list-group.html"> <span class="nav-text">List Group</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="modal.html"> <span class="nav-text">Modal</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="pagination.html"> <span class="nav-text">Pagination</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="popover-tooltip.html"> <span class="nav-text">Popover & Tooltip</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="progress-bar.html"> <span class="nav-text">Progress Bar</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="spinner.html"> <span class="nav-text">Spinner</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="switches.html"> <span class="nav-text">Switches</span> </a>
									</li>
									<li class="has-sub">
										<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#tables" aria-expanded="false" aria-controls="tables"> <span class="nav-text">Tables</span> <b class="caret"></b> </a>
										<ul class="collapse" id="tables">
											<div class="sub-menu">
												<li> <a href="bootstarp-tables.html">Bootstrap Tables</a> </li>
												<li> <a href="data-tables.html">Data Tables</a> </li>
											</div>
										</ul>
									</li>
									<li>
										<a class="sidenav-item-link" href="tab.html"> <span class="nav-text">Tab</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="toaster.html"> <span class="nav-text">Toaster</span> </a>
									</li>
									<li class="has-sub">
										<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#icons" aria-expanded="false" aria-controls="icons"> <span class="nav-text">Icons</span> <b class="caret"></b> </a>
										<ul class="collapse" id="icons">
											<div class="sub-menu">
												<li> <a href="material-icons.html">Material Icon</a> </li>
												<li> <a href="flag-icons.html">Flag Icon</a> </li>
											</div>
										</ul>
									</li>
									<li class="has-sub">
										<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#forms" aria-expanded="false" aria-controls="forms"> <span class="nav-text">Forms</span> <b class="caret"></b> </a>
										<ul class="collapse" id="forms">
											<div class="sub-menu">
												<li> <a href="basic-input.html">Basic Input</a> </li>
												<li> <a href="input-group.html">Input Group</a> </li>
												<li> <a href="checkbox-radio.html">Checkbox & Radio</a> </li>
												<li> <a href="form-validation.html">Form Validation</a> </li>
												<li> <a href="form-advance.html">Form Advance</a> </li>
											</div>
										</ul>
									</li>
									<li class="has-sub">
										<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#maps" aria-expanded="false" aria-controls="maps"> <span class="nav-text">Maps</span> <b class="caret"></b> </a>
										<ul class="collapse" id="maps">
											<div class="sub-menu">
												<li> <a href="google-maps.html">Google Map</a> </li>
												<li> <a href="vector-maps.html">Vector Map</a> </li>
											</div>
										</ul>
									</li>
									<li class="has-sub">
										<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#widgets" aria-expanded="false" aria-controls="widgets"> <span class="nav-text">Widgets</span> <b class="caret"></b> </a>
										<ul class="collapse" id="widgets">
											<div class="sub-menu">
												<li> <a href="widgets-general.html">General Widget</a> </li>
												<li> <a href="widgets-chart.html">Chart Widget</a> </li>
											</div>
										</ul>
									</li>
								</div>
							</ul>
						</li>
						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#charts" aria-expanded="false" aria-controls="charts"> <i class="mdi mdi-chart-pie"></i> <span class="nav-text">Charts</span> <b class="caret"></b> </a>
							<ul class="collapse" id="charts" data-parent="#sidebar-menu">
								<div class="sub-menu">
									<li>
										<a class="sidenav-item-link" href="apex-charts.html"> <span class="nav-text">Apex Charts</span> </a>
									</li>
								</div>
							</ul>
						</li>
						<li class="section-title"> Pages </li>
						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#users" aria-expanded="false" aria-controls="users"> <i class="mdi mdi-image-filter-none"></i> <span class="nav-text">User</span> <b class="caret"></b> </a>
							<ul class="collapse" id="users" data-parent="#sidebar-menu">
								<div class="sub-menu">
									<li>
										<a class="sidenav-item-link" href="user-profile.html"> <span class="nav-text">User Profile</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="user-activities.html"> <span class="nav-text">User Activities</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="user-profile-settings.html"> <span class="nav-text">User Profile Settings</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="user-account-settings.html"> <span class="nav-text">User Account Settings</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="user-planing-settings.html"> <span class="nav-text">User Planing Settings</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="user-billing.html"> <span class="nav-text">User billing</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="user-notify-settings.html"> <span class="nav-text">User Notify Settings</span> </a>
									</li>
								</div>
							</ul>
						</li>
						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#authentication" aria-expanded="false" aria-controls="authentication"> <i class="mdi mdi-account"></i> <span class="nav-text">Authentication</span> <b class="caret"></b> </a>
							<ul class="collapse" id="authentication" data-parent="#sidebar-menu">
								<div class="sub-menu">
									<li>
										<a class="sidenav-item-link" href="sign-in.html"> <span class="nav-text">Sign In</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="sign-up.html"> <span class="nav-text">Sign Up</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="reset-password.html"> <span class="nav-text">Reset Password</span> </a>
									</li>
								</div>
							</ul>
						</li>
						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#other-page" aria-expanded="false" aria-controls="other-page"> <i class="mdi mdi-file-multiple"></i> <span class="nav-text">Other pages</span> <b class="caret"></b> </a>
							<ul class="collapse" id="other-page" data-parent="#sidebar-menu">
								<div class="sub-menu">
									<li>
										<a class="sidenav-item-link" href="invoice.html"> <span class="nav-text">Invoice</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="404.html"> <span class="nav-text">404 page</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="page-comingsoon.html"> <span class="nav-text">Coming Soon</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="page-maintenance.html"> <span class="nav-text">Maintenance</span> </a>
									</li>
								</div>
							</ul>
						</li>
						<li class="section-title"> Documentation </li>
						<li class="{{ request()->is('admin/getting') ? 'active': '' }}">
							<a class="sidenav-item-link" href="getting-started.html"> <i class="mdi mdi-airplane"></i> <span class="nav-text">Getting Started</span> </a>
						</li>
						<li class="has-sub">
							<a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#customization" aria-expanded="false" aria-controls="customization"> <i class="mdi mdi-square-edit-outline"></i> <span class="nav-text">Customization</span> <b class="caret"></b> </a>
							<ul class="collapse" id="customization" data-parent="#sidebar-menu">
								<div class="sub-menu">
									<li>
										<a class="sidenav-item-link" href="navbar-customization.html"> <span class="nav-text">Navbar</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="sidebar-customization.html"> <span class="nav-text">Sidebar</span> </a>
									</li>
									<li>
										<a class="sidenav-item-link" href="styling.html"> <span class="nav-text">Styling</span> </a>
									</li>
								</div>
							</ul>
						</li>
					</ul>
				</div>
				<div class="sidebar-footer">
					<div class="sidebar-footer-content">
						<ul class="d-flex">
							<li> <a href="user-account-settings.html" data-toggle="tooltip" title="Profile settings"><i class="mdi mdi-settings"></i></a></li>
							<li> <a href="#" data-toggle="tooltip" title="No chat messages"><i class="mdi mdi-chat-processing"></i></a> </li>
						</ul>
					</div>
				</div>
			</div>
		</aside>
		<!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
		<div class="page-wrapper">
			<!-- Header -->
			<header class="main-header" id="header">
				<nav class="navbar navbar-expand-lg navbar-light" id="navbar">
					<!-- Sidebar toggle button -->
					<button id="sidebar-toggler" class="sidebar-toggle"> <span class="sr-only">Toggle navigation</span> </button> <span class="page-title"></span>
					<div class="navbar-right ">
						<!-- search form -->
						<div class="search-form">
							<form action="index.html" method="get">
								<div class="input-group input-group-sm" id="input-group-search">
									<input type="text" autocomplete="off" name="query" id="search-input" class="form-control" placeholder="Search..." />
									<div class="input-group-append">
										<button class="btn" type="button">/</button>
									</div>
								</div>
							</form>
							<ul class="dropdown-menu dropdown-menu-search">
								<li class="nav-item"> <a class="nav-link" href="index.html">Morbi leo risus</a> </li>
								<li class="nav-item"> <a class="nav-link" href="index.html">Dapibus ac facilisis in</a> </li>
								<li class="nav-item"> <a class="nav-link" href="index.html">Porta ac consectetur ac</a> </li>
								<li class="nav-item"> <a class="nav-link" href="index.html">Vestibulum at eros</a> </li>
							</ul>
						</div>
						<ul class="nav navbar-nav">
							<!-- Offcanvas -->
							<!-- User Account -->
							<li class="dropdown user-menu">
								<button class="dropdown-toggle nav-link" data-toggle="dropdown"> <img src="{{ asset('assets/admin/images/user/user-xs-01.jpg') }}" class="user-image rounded-circle" alt="User Image" /> <span class="d-none d-lg-inline-block">John Doe</span> </button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li>
										<a class="dropdown-link-item" href="{{ route('admin.profile.edit') }}"> <i class="mdi mdi-account-outline"></i> <span class="nav-text">My Profile</span> </a>
									</li>
									<li>
										<a class="dropdown-link-item" href="email-inbox.html"> <i class="mdi mdi-email-outline"></i> <span class="nav-text">Message</span> <span class="badge badge-pill badge-primary">24</span> </a>
									</li>
									<li>
										<a class="dropdown-link-item" href="user-activities.html"> <i class="mdi mdi-diamond-stone"></i> <span class="nav-text">Activitise</span></a>
									</li>
									<li>
										<a class="dropdown-link-item" href="user-account-settings.html"> <i class="mdi mdi-settings"></i> <span class="nav-text">Account Setting</span> </a>
									</li>
									<li class="dropdown-footer">
										<a href="{{ route('logout') }}" class="dropdown-link-item" href="sign-in.html"> <i class="mdi mdi-logout"></i> Log Out </a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			<!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
			@yield('content')

			<!-- Footer -->
			<footer class="footer mt-auto">
				<div class="copyright bg-white">
					<p> &copy; <span id="copy-year"></span> Copyright Template by <a class="text-primary" href="http://www.cdlcell.com" target="_blank">CDL</a>. </p>
				</div>
				<script>
				var d = new Date();
				var year = d.getFullYear();
				document.getElementById("copy-year").innerHTML = year;
				</script>
			</footer>
		</div>
	</div>

    @include('admin.includes.scripts')
    @yield('scripts')
</body>

</html>
