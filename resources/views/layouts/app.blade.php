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
								<li role='separator' class='divider'></li>
                                <li><a href="{{ url('/user_profile') }}"><i class="fa fa-btn fa-sign-out"></i>User Profile</a></li>
                                <li><a href="{{ url('/change_password') }}"><i class="fa fa-btn fa-sign-out"></i>Change Password</a></li>
                                <li><a href="{{ url('/queue_locations') }}"><i class="fa fa-btn"></i>Set Location</a></li>
                                <li><a href="{{ url('/wards') }}"><i class="fa fa-btn"></i>Set Ward</a></li>
								<li role='separator' class='divider'></li>
                                <li><a href="{{ url('/manual.pdf') }}">Manual</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
	<div class='container'>
		<br>
		<div class='row'>
			<div class='col-md-3'>
				<!--
				<div align='center'>
					<img src='/msu_logo.png'>
				</div>
				<br>
				-->
				<ul class='list-group'>
					<li class='list-group-item'>
							<h5>
							<strong>
							{{ Auth::user()->authorization->author_name }}
							</strong>
							</h5>
							
					</li>
				</ul>
				@can('system-administrator')
				<div class='list-group'>
					<a class='list-group-item' href="{{ url('/maintenance') }}"><span class='glyphicon glyphicon-cog'></span>&nbsp; Maintenance</a>
					<a class='list-group-item' href="{{ url('/users') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Users</a>
					<a class='list-group-item' href="{{ url('/user_authorizations') }}"><span class='glyphicon glyphicon-flag'></span>&nbsp; Authorizations</a>
				</div>
				@endcan
				@can('system-administrator')
						<h4>Patient</h4>
				@endcan	
				@can('module-patient')
				<div class='list-group'>
						<a class='list-group-item' href="{{ url('/patients') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Patient Registration</a>
						<a class='list-group-item' href="{{ url('/appointments') }}"><span class='glyphicon glyphicon-calendar'></span>&nbsp; Appointments</a>
						<a class='list-group-item' href="{{ url('/queues') }}"><span class='glyphicon glyphicon-th-list'></span>&nbsp; Queues</a>
						<a class='list-group-item' href="{{ url('/admissions') }}"><span class='glyphicon glyphicon-bed'></span>&nbsp; Admissions</a>
						<a class='list-group-item' href="{{ url('/bed_bookings?type=preadmission') }}"><span class='glyphicon glyphicon-time'></span>&nbsp; Preadmissions</a></li>
						<a class='list-group-item' href="{{ url('/discharges') }}"><span class='glyphicon glyphicon-home'></span>&nbsp; Discharges</a>
				</div>
				<div class='list-group'>
						<a class='list-group-item' href="{{ url('/beds') }}"><span class='glyphicon glyphicon-cog'></span>&nbsp; Beds </a></li>
				</div>
				@endcan
				@can('system-administrator')
						<h4>Consultation</h4>
				@endcan	
				@can('module-consultation')
				<div class='list-group'>
					<a class='list-group-item' href="/patient_lists"><span class='glyphicon glyphicon-user'></span>&nbsp; Patient List</a>
					<a class='list-group-item' href="/consultations"><span class='glyphicon glyphicon-comment'></span>&nbsp; Consultation List</a>
				</div>
				@if (!empty($consultation) && !empty($patient))
				<div class='list-group'>
					<a class='list-group-item' href="/medical_alerts"><span class='glyphicon glyphicon-exclamation-sign'></span>&nbsp; Medical Alerts</a>
					@if ($consultation->encounter->encounter_code=='inpatient')
					<a class='list-group-item' href="/diet"><span class='glyphicon glyphicon-cutlery'></span>&nbsp; Diet</a>
					@endif
					@if ($patient->gender_code=='P')
					<a class='list-group-item' href="/obstetric"><span class='glyphicon glyphicon-user'></span>&nbsp; Obstetric History</a>
					<a class='list-group-item' href="/newborns"><span class='glyphicon glyphicon-baby-formula'></span>&nbsp; Newborn</a>
					@endif
					<a class='list-group-item' href="/medical_certificates/create"><span class='glyphicon glyphicon-credit-card'></span>&nbsp; Medical Certificate</a>
				</div>
				<div class='list-group'>
					<a class='list-group-item' href="/documents?patient_mrn={{ $patient->patient_mrn }}"><span class='glyphicon glyphicon-folder-close'></span>&nbsp; Documents</a>
				</div>
				@endif
				@endcan
				@can('system-administrator')
						<h4>Diet</h4>
				@endcan	
				@can('module-diet')
				<div class='list-group'>
								<a class='list-group-item' href="{{ url('/diet_orders') }}"><span class='glyphicon glyphicon-asterisk'></span>&nbsp; Diet Orders</a>
								<a class='list-group-item' href="{{ url('/diet_menus') }}"><span class='glyphicon glyphicon-cutlery'></span>&nbsp; Diet Menus</a>
								<a class='list-group-item' href="{{ url('/diet_cooklist') }}"><span class='glyphicon glyphicon-file'></span>&nbsp; Diet Cooklist</a>
								<a class='list-group-item' href="{{ url('/diet_bom') }}"><span class='glyphicon glyphicon-th-large'></span>&nbsp; Diet Bill of Materials</a>
								<a class='list-group-item' href="{{ url('/diet_workorder') }}"><span class='glyphicon glyphicon-ok-sign'></span>&nbsp; Diet Work Order</a>
								<a class='list-group-item' href="{{ url('/diet_distribution') }}"><span class='glyphicon glyphicon-random'></span>&nbsp; Diet Distribution</a>
				</div>
				<div class='list-group'>
								<a class='list-group-item' href="{{ url('/diet_complains') }}"><span class='glyphicon glyphicon-thumbs-down'></span>&nbsp; Diet Complains</a>
								<a class='list-group-item' href="{{ url('/diet_wastages') }}"><span class='glyphicon glyphicon-trash'></span>&nbsp; Diet Wastages</a>
								<a class='list-group-item' href="{{ url('/diet_qualities') }}"><span class='glyphicon glyphicon-star'></span>&nbsp; Diet Qualities</a>
				</div>
				@endcan
				@can('system-administrator')
						<h4>Inventory</h4>
				@endcan	
				@can('module-inventory')
				<div class='list-group'>
								<a class='list-group-item' href="{{ url('/products') }}"><span class='glyphicon glyphicon-glass'></span>&nbsp; Products</a>
								<a class='list-group-item' href="{{ url('/purchase_orders') }}"><span class='glyphicon glyphicon-briefcase'></span>&nbsp; Purchase Orders</a>
								<a class='list-group-item' href="{{ url('/suppliers') }}"><span class='glyphicon glyphicon-shopping-cart'></span>&nbsp; Suppliers</a>
								<a class='list-group-item' href="{{ url('/stores') }}"><span class='glyphicon glyphicon-lamp'></span>&nbsp; Stores</a>
								<a class='list-group-item' href="{{ url('/sets') }}"><span class='glyphicon glyphicon-apple'></span>&nbsp; Order Sets</a>
								<a class='list-group-item' href="{{ url('/product_authorizations') }}"><span class='glyphicon glyphicon-barcode'></span>&nbsp; Product Authorizations</a>
				</div>
				<div class='list-group'>
								<a class='list-group-item' href="{{ url('/loans') }}"><span class='glyphicon glyphicon-transfer'></span>&nbsp; Loans</a>
				</div>
				@endcan
				@can('system-administrator')
						<h4>Ward</h4>
				@endcan	
				@can('module-ward')
				<div class='list-group'>
								<a class='list-group-item' href="{{ url('/admissions') }}"><span class='glyphicon glyphicon-bed'></span>&nbsp; Admissions</a>
								<a class='list-group-item' href="{{ url('/admission_tasks') }}"><span class='glyphicon glyphicon-tasks'></span>&nbsp; Admission Tasks</a>
								<a class='list-group-item' href="{{ url('/bed_bookings') }}"><span class='glyphicon glyphicon-bookmark'></span>&nbsp; Bed Reservations</a>
				</div>
				@if (!empty($ward->ward_code))
				@if ($ward->ward_code != 'mortuary')
				<div class='list-group'>
								<a class='list-group-item' href="{{ url('/patients') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Patients</a>
								<a class='list-group-item' href="{{ url('/appointments') }}"><span class='glyphicon glyphicon-calendar'></span>&nbsp; Appointments</a>
				</div>
				@endif
				@endif
				<div class='list-group'>
								<a class='list-group-item' href="{{ url('/products') }}"><span class='glyphicon glyphicon-glass'></span>&nbsp; Products</a>
								<a class='list-group-item' href="{{ url('/loans/ward') }}"><span class='glyphicon glyphicon-transfer'></span>&nbsp; Loans</a>
				</div>
				@endcan
				@can('system-administrator')
						<h4>Medical Record</h4>
				@endcan	
				@can('module-medical-record')
				<div class='list-group'>
						<a class='list-group-item' href="{{ url('/patients') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Patient List</a>
						<a class='list-group-item' href="{{ url('/loans?type=folder') }}"><span class='glyphicon glyphicon-transfer'></span>&nbsp; Loans</a>
				</div>
				@endcan
				@can('system-administrator')
						<h4>Financial</h4>
				@endcan	
				@can('module-discharge')
				<div class='list-group'>
						<a class='list-group-item' href="{{ url('/patients') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Patient List</a>
						<a class='list-group-item' href="{{ url('/discharges') }}"><span class='glyphicon glyphicon-home'></span>&nbsp; Discharges</a>
				</div>
				@endcan
				@can('system-administrator')
						<h4>Support</h4>
				@endcan	
				@can('module-support')
				<div class='list-group'>
						<a class='list-group-item' href="{{ url('/order_queues') }}"><span class='glyphicon glyphicon-tasks'></span>&nbsp; Outpatient Tasks</a>
						<a class='list-group-item' href="{{ url('/admission_tasks') }}"><span class='glyphicon glyphicon-tasks'></span>&nbsp; Inpatient Tasks</a>
				</div>
				@endcan
				<div class='list-group'>
						<a class='list-group-item' href="{{ url('/reports') }}"><span class='glyphicon glyphicon-bullhorn'></span>&nbsp; Reports</a>
				</div>
			</div>
			<div class='col-md-9'>
				<div class='panel panel-default'>
					<div class='panel-body fixed-panel'>
						@yield('content')
					</div>	
				</div>	
			</div>	
		</div>	
	</div>
	<script type="text/javascript">
		/*
		$(document).ready(function () {
		 
				window.setTimeout(function() {
					$(".alert-info").fadeTo(1500, 0).slideUp(500, function(){
						$(this).remove(); 
					});
				}, 2000);

				 
		});
		*/

		$(function () {
			  $('[data-toggle="tooltip"]').tooltip()
		})	  

		function goBack() {
				window.history.back();
		}					
	</script>
</body>
