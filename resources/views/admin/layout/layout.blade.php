<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/" />

	<title> Admin </title>
    
	<link href="{{ asset('admin/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .navbar-bg {
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,.1);
        }
        .sidebar-item.active .sidebar-link {
            background: linear-gradient(90deg, rgba(59, 125, 221, .1), rgba(59, 125, 221, .0875) 50%, transparent);
            border-left-color: #3b7ddd;
            color: #e9ecef;
        }
        .sidebar-item.active i {
            color: #3b7ddd;
        }
    </style>
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
                    <span class="align-middle">{{env('APP_NAME')}}</span>
                </a>

				<ul class="sidebar-nav">
					<!-- Dashboard -->
					<li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                            <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    <!-- Products -->
					<li class="sidebar-header">
						Products
					</li>

					<li class="sidebar-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.products.index') }}">
                            <i class="align-middle" data-feather="package"></i> <span class="align-middle">View Products</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.products.create') }}">
                            <i class="align-middle" data-feather="plus-square"></i> <span class="align-middle">Add Product</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.products.logs') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.products.logs') }}">
                            <i class="align-middle" data-feather="activity"></i> <span class="align-middle">Product Logs</span>
                        </a>
                    </li>

                    <!-- Orders -->
					<li class="sidebar-header">
						Orders
					</li>

					<li class="sidebar-item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.invoices.index') }}">
                            <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">View Orders</span>
                        </a>
					</li>
				</ul>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator">4</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									4 New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-danger" data-feather="alert-circle"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Update completed</div>
												<div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
												<div class="text-muted small mt-1">30m ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all notifications</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img src="{{ asset('admin/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="Admin" /> 
                                <span class="text-dark">Admin</span>
                            </a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); 
                                this.closest('form').submit();">
                                    <i class="align-middle me-1" data-feather="log-out"></i> Log out
                                </a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

            @yield('content')
		</div>
	</div>

	<script src="{{ asset('admin/js/app.js') }}"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialize Feather Icons
        document.addEventListener("DOMContentLoaded", function() {
            feather.replace();
        });

        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `@foreach($errors->all() as $error)
                    <div class="text-left">{{ $error }}</div>
                @endforeach`,
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>