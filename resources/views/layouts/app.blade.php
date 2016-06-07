<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <title>{{ Config::get('host.application_name') }}</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css">
		<link href="/assets/bootstrap/css/starter-template.css" rel="stylesheet">
		<script src="/assets/js/moment.min.2.5.0.js"></script>
		<script src="/assets/js/combodate.js"></script>
</head>
<body id="app-layout"> 
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#spark-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
					{{ Config::get('host.application_name') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
	<div class='container'>

		<h6>
		<ul class='nav nav-pills'>
					@can('module-patient')
					<li role="presentation" class="dropdown">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
							  <span class='glyphicon glyphicon-user'></span>&nbsp; Patient <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{ url('/patients') }}">Patients</a></li>
								<li><a href="{{ url('/appointments') }}">Appointments</a></li>
								<li><a href="{{ url('/encounters') }}">Encounters</a></li>
								<li><a href="{{ url('/queues') }}">Location Queues</a></li>
								<li><a href="{{ url('/order_queues') }}">Order Queues</a></li>
							</ul>
					</li>
					@endcan
					@can('module-consultation')
					<li role="presentation" class="dropdown">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
							  <span class='glyphicon glyphicon-comment'></span>&nbsp; Consultations <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{ url('/patient_lists') }}">Patient List</a></li>
								<li><a href="{{ url('/consultations') }}">Consultation List</a></li>
							</ul>
					</li>
					@endcan
					@can('module-inventory')
					<li role="presentation" class="dropdown">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
							  <span class='glyphicon glyphicon-shopping-cart'></span>&nbsp; Inventory <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{ url('/products') }}">Products</a></li>
								<li><a href="{{ url('/purchase_orders') }}">Purchase Orders</a></li>
								<li><a href="{{ url('/suppliers') }}">Suppliers</a></li>
								<li><a href="{{ url('/stores') }}">Stores</a></li>
								<li><a href="{{ url('/sets') }}">Order Sets</a></li>
							</ul>
					</li>
					@endcan
					@can('module-ward')
					<li role="presentation" class="dropdown">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
							  <span class='glyphicon glyphicon-bed'></span>&nbsp; Ward <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="{{ url('/admissions') }}">Admissions</a></li> 
								<li><a href="{{ url('/bed_bookings') }}">Bed Bookings</a></li> 
								<li><a href="{{ url('/admission_tasks') }}">Nurse Task</a></li> 
							</ul>
					</li>
					@endcan
					@can('system-administrator')
					<li><a class='btn btn-default' href="{{ url('/options') }}"><span class='glyphicon glyphicon-th-list'></span>&nbsp; Options</a></li>
					@endcan
		</ul>
		</h6>

    @yield('content')
	</div>
	<script type="text/javascript">
		$(document).ready(function () {
		 
				window.setTimeout(function() {
					$(".alert-info").fadeTo(1500, 0).slideUp(500, function(){
						$(this).remove(); 
					});
				}, 2000);

				 
		});

	
	</script>
</body>
