<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ Config::get('host.application_name') }}</title>
<link href="/assets/inspinia/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="/assets/inspinia/css/animate.css" rel="stylesheet">
<link href="/assets/inspinia/css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="/assets/inspinia/css/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="/assets/inspinia/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="/assets/inspinia/css/plugins/codemirror/codemirror.css" rel="stylesheet">
<link href="/assets/inspinia/css/style.css" rel="stylesheet">
<link href="/assets/inspinia/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="/assets/inspinia/css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
<link href="/assets/inspinia/css/plugins/iCheck/custom.css" rel="stylesheet">


<!-- Mainly scripts -->
<script src="/assets/inspinia/js/jquery-2.1.1.js"></script>
<script src="/assets/inspinia/js/bootstrap.min.js"></script>
<script src="/assets/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/assets/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/assets/inspinia/js/inspinia.js"></script>
<script src="/assets/inspinia/js/plugins/pace/pace.min.js"></script>

<!-- Data picker -->
<script src="/assets/inspinia/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- Clock picker -->
<script src="/assets/inspinia/js/plugins/clockpicker/clockpicker.js"></script>

<!-- Flot -->
<script src="/assets/inspinia/js/plugins/flot/jquery.flot.js"></script>
<script src="/assets/inspinia/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/assets/inspinia/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="/assets/inspinia/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="/assets/inspinia/js/plugins/flot/jquery.flot.pie.js"></script>
<script src="/assets/inspinia/js/plugins/flot/jquery.flot.symbol.js"></script>
<script src="/assets/inspinia/js/plugins/flot/curvedLines.js"></script>

<!-- Peity -->
<script src="/assets/inspinia/js/plugins/peity/jquery.peity.min.js"></script>
<script src="/assets/inspinia/js/demo/peity-demo.js"></script>


<!-- ChartJS-->
<script src="/assets/inspinia/js/plugins/chartJs/Chart.min.js"></script>
	
<!-- FooTable -->
<script src="/assets/inspinia/js/plugins/footable/footable.all.min.js"></script>

<!-- Jasny -->
<script src="/assets/inspinia/js/plugins/jasny/jasny-bootstrap.min.js"></script>

<!-- DROPZONE -->
<script src="/assets/inspinia/js/plugins/dropzone/dropzone.js"></script>

<!-- CodeMirror -->
<script src="/assets/inspinia/js/plugins/codemirror/codemirror.js"></script>
<script src="/assets/inspinia/js/plugins/codemirror/mode/xml/xml.js"></script>

<!-- Toastr -->
<link href="/assets/inspinia/css/plugins/toastr/toastr.min.css" rel="stylesheet">
<script src="/assets/inspinia/js/plugins/toastr/toastr.min.js"></script>

<!-- iCheck -->

<script src="/assets/inspinia/js/plugins/iCheck/icheck.min.js"></script>

		<script src="/assets/js/moment.min.2.5.0.js"></script>
		<script src="/assets/js/combodate.js"></script>
		<script src="/assets/js/js.cookie.js"></script>
<style>
		#toast-container > .toast {
			background-image: none !important;
		}

		#toast-container > .toast:before {
				position: fixed;
				font-family: FontAwesome;
				font-size: 24px;
				line-height: 18px;
				float: left;
				color: #FFF;
				padding-right: 0.5em;
				margin: auto 0.5em auto -1.5em;
		}        
		#toast-container > .toast-warning:before {
		content: "\f06a";
		}
		#toast-container > .toast-error:before {
		content: "\f071";
		}
		#toast-container > .toast-info:before {
		content: "\f005";
		}
		#toast-container > .toast-success:before {
		content: "\f00c";
		}

</style>
</head>

<?php
if (!isset($_COOKIE['his-navbar'])) {
	setcookie('his-navbar',1,time()+(86400*7));
	$_COOKIE['his-navbar']=1;
}
if ($_COOKIE['his-navbar']==1) {
?>
<body class="mini-navbar full-height-layout">
<?php } else { ?>
<body class="full-height-layout">
<?php } ?>
    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
					<div class="dropdown profile-element"> 
						<span>
                            <img alt="image" class="img-circle" width='40' heigth='40' src="/assets/avatar.jpg" />
					 	</span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong></span>  
						</a>
								{{ Auth::user()->authorization->author_name }}
                    </div>
					<div class="logo-element">
						<?php
							$name = Auth::user()->name;
							$name = strtolower($name);
							$name = str_replace(" bin ", "", $name);
							$name = str_replace("dr.", "", $name);
							$name = strtoupper(trim($name));
							$initials = explode(' ', $name);
							$initials[0] = trim(substr($initials[0],0,1));
							if(!empty($initials[1])) { $initials[1] = substr(trim($initials[1]),0,1); }
						?>
						{{ $initials[0] }}{{ $initials[1] }}
                    </div>
                </li>
				@can('system-administrator')
					<li><a href="{{ url('/maintenance') }}"><span class='glyphicon glyphicon-cog'></span>&nbsp; Maintenance</a></li>
					<li><a href="{{ url('/users') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Users</a></li>
					<li><a href="{{ url('/user_authorizations') }}"><span class='glyphicon glyphicon-flag'></span>&nbsp; Authorizations</a></li>
				@endcan
				<!-- Patient Module -->
				@can('system-administrator')
						<h4>&nbsp;Patient</h4>
				@endcan
				@can('module-patient')
				<li><a href="{{ url('/patients') }}" title='Patients'><i class="fa fa-user"></i><span class='nav-label'>Patients</span></a></li>
				<li><a href="{{ url('/appointments') }}"><i class="fa fa-calendar"></i><span class='nav-label'>Appointments</span></a></li>
				<li><a href="{{ url('/queues') }}"><i class="fa fa-th-list"></i><span class='nav-label'>Queues</span></a></li>
				<li><a href="{{ url('/admissions') }}"><i class="fa fa-hospital-o"></i><span class='nav-label'>Admissions</span></a></li>
				<li><a href="{{ url('/bed_bookings?type=preadmission') }}"><i class="glyphicon glyphicon-time"></i><span class='nav-label'>Preadmissions</span></a></li>
				<li><a href="{{ url('/discharges') }}"><i class="fa fa-home"></i><span class='nav-label'>Discharges</span></a></li>
				<li><a href="{{ url('/beds') }}"><i class="glyphicon glyphicon-bed"></i><span class='nav-label'>Beds</span></a></li>
				@endcan
				@can('system-administrator')
						<h4>&nbsp;Consultation</h4>
				@endcan	
				<!-- Consultation Module -->
				@can('module-consultation')
				<li><a href="/patient_lists"><i class="fa fa-stethoscope"></i><span class='nav-label'>Patient List</span></a></li>
				<li><a href="/consultations"><i class="fa fa-comments-o"></i><span class='nav-label'>Consultation List</span></a></li>
				<h4>&nbsp;</h4>
				@if (!empty($consultation) && !empty($patient))
					<li><a href="/medical_alerts"><i class="fa fa-exclamation-circle"></i><span class='nav-label'>Medical Alerts</span></a></li>
					@if ($consultation->encounter->encounter_code=='inpatient')
					<li><a href="/diet"><i class="fa fa-cutlery"></i><span class='nav-label'>Diet</a></li>
					@endif
					@if ($patient->gender_code=='P')
					<li><a href="/obstetric"><i class="fa fa-user"></i><span class='nav-label'>Obstetric History</span></a></li>
					<li><a href="/newborns"><i class="glyphicon glyphicon-baby-formula"></i><span class='nav-label'>Newborn</span></a></li>
					@endif
					<li><a href="/medical_certificates/create"><i class="fa fa-certificate"></i><span class='nav-label'>Medical Certificate</span></a></li>
					<li><a href="/documents?patient_mrn={{ $patient->patient_mrn }}"><i class="fa fa-files-o"></i><span class='nav-label'>Documents</span></a></li>
				@endif
				@endcan

				<!-- Diet Module -->
				@can('system-administrator')
						<h4>&nbsp;Diet</h4>
				@endcan	
				@can('module-diet')
				<li><a href="{{ url('/diet_orders') }}"><span class='glyphicon glyphicon-asterisk'></span>&nbsp; Diet Orders</a></li>
				<li><a href="{{ url('/diet_menus') }}"><span class='glyphicon glyphicon-cutlery'></span>&nbsp; Diet Menus</a></li>
				<li><a href="{{ url('/diet_cooklist') }}"><span class='glyphicon glyphicon-file'></span>&nbsp; Diet Cooklist</a></li>
				<li><a href="{{ url('/diet_bom') }}"><span class='glyphicon glyphicon-th-large'></span>&nbsp; Diet Bill of Materials</a></li>
				<li><a href="{{ url('/diet_workorder') }}"><span class='glyphicon glyphicon-ok-sign'></span>&nbsp; Diet Work Order</a></li>
				<li><a href="{{ url('/diet_distribution') }}"><span class='glyphicon glyphicon-random'></span>&nbsp; Diet Distribution</a></li>
				<h4>&nbsp;</h4>
				<li><a href="{{ url('/diet_complains') }}"><span class='glyphicon glyphicon-thumbs-down'></span>&nbsp; Diet Complains</a></li>
				<li><a href="{{ url('/diet_wastages') }}"><span class='glyphicon glyphicon-trash'></span>&nbsp; Diet Wastages</a></li>
				<li><a href="{{ url('/diet_qualities') }}"><span class='glyphicon glyphicon-star'></span>&nbsp; Diet Qualities</a></li>
				<h4>&nbsp;</h4>
				@endcan

				<!-- Inventory Module -->
				@can('system-administrator')
						<h4>&nbsp;Inventory</h4>
				@endcan	
				@can('module-inventory')
				<li><a title='Products' href="{{ url('/products') }}"><i class='fa fa-glass'></i><span class='nav-label'>Products</span></a></li>
				<li><a title='Purchase Orders' href="{{ url('/purchase_orders') }}"><i class='fa fa-shopping-cart'></i><span class='nav-label'>Purchase Orders</span></a></li>
				<li><a title='Suppliers' href="{{ url('/suppliers') }}"><i class='fa fa-truck' aria-hidden='true'></i><span class='nav-label'>Suppliers</span></a></li>
				<li><a title='Stores' href="{{ url('/stores') }}"><i class='fa fa-th-large'></i><span class='nav-label'>Stores</span></a></li>
				<li><a title='Order Sets' href="{{ url('/sets') }}"><i class='fa fa-medkit'></i><span class='nav-label'>Order Sets</span></a></li>
				<li><a title='Product Authorizations' href="{{ url('/product_authorizations') }}"><i class='fa fa-barcode'></i><span class='nav-label'>Product Authorizations</span></a></li>
				<li><a title='Loans' href="{{ url('/loans') }}"><i class='glyphicon glyphicon-transfer'></i><span class='nav-label'><span class='nav-label'>Loans</span></a></li>
				@endcan

				<!-- Ward Module -->
				@can('system-administrator')
						<h4>&nbsp;Ward</h4>
				@endcan	
				@can('module-ward')
				<li><a href="{{ url('/admissions') }}"><span class='glyphicon glyphicon-bed'></span>&nbsp; Admissions</a></li>
				<li><a href="{{ url('/admission_tasks') }}"><span class='glyphicon glyphicon-tasks'></span>&nbsp; Admission Tasks</a></li>
				<li><a href="{{ url('/bed_bookings') }}"><span class='glyphicon glyphicon-bookmark'></span>&nbsp; Bed Reservations</a></li>
				@if (!empty($ward->ward_code))
						@if ($ward->ward_code != 'mortuary')
						<li><a href="{{ url('/patients') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Patients</a></li>
						<li><a href="{{ url('/appointments') }}"><span class='glyphicon glyphicon-calendar'></span>&nbsp; Appointments</a></li>
						@endif
				@endif
				<h4>&nbsp;</h4>
				<li><a href="{{ url('/products') }}"><span class='glyphicon glyphicon-glass'></span>&nbsp; Products</a></li>
				<li><a href="{{ url('/loans/ward') }}"><span class='glyphicon glyphicon-transfer'></span>&nbsp; Loans</a></li>
				@endcan

				<!-- Medical Record -->
				@can('system-administrator')
						<h4>&nbsp;Medical Record</h4>
				@endcan	
				@can('module-medical-record')
				<li><a href="{{ url('/patients') }}"><span class='glyphicon glyphicon-user'></span>&nbsp; Patient List</a></li>
				<li><a href="{{ url('/loans?type=folder') }}"><span class='glyphicon glyphicon-transfer'></span>&nbsp; Loans</a></li>
				@endcan

				<!-- Financial Module -->
				@can('system-administrator')
						<h4>&nbsp;Financial</h4>
				@endcan	
				@can('module-discharge')
				<li><a title='Patient List' href="{{ url('/patients') }}"><i class='fa fa-stethoscope'></i><span class='nav-label'>Patient List</span></a></li>
				<li><a title='Discharges' href="{{ url('/discharges') }}"><i class='fa fa-home'></i><span class='nav-label'>Discharges</span></a></li>
				@endcan

				<!-- Support -->
				@can('system-administrator')
						<h4>&nbsp;Support</h4>
				@endcan	
				@can('module-support')
				<li><a href="{{ url('/order_queues') }}"><i class='fa fa-check-square'></i><span class='nav-label'>Outpatient Tasks</span></a></li>
				<li><a href="{{ url('/admission_tasks') }}"><i class='fa fa-check-square-o'></i><span class='nav-label'>Inpatient Tasks<span></a></li>
				@endcan

				<!-- Report -->
				<li><a href="{{ url('/reports') }}"><i class="fa fa-bar-chart"></i><span class='nav-label'>Reports</span></a></li>
            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="white-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" onclick='setBarState()'><i class="fa fa-bars"></i> </a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                Options <i class="fa fa-cog"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}">Logout</a></li>
								<li role='separator' class='divider'></li>
                                <li><a href="{{ url('/user_profile') }}">User Profile</a></li>
                                <li><a href="{{ url('/change_password') }}">Change Password</a></li>
                                <li><a href="{{ url('/queue_locations') }}">Set Location</a></li>
                                <li><a href="{{ url('/wards') }}">Set Ward</a></li>
								<li role='separator' class='divider'></li>
                                <li><a href="{{ url('/manual.pdf') }}">Manual</a></li>
                            </ul>
                        </li>
            </ul>

        </nav>
        </div>
            <div class="row full-height">
                    <div class="full-height-scroll white-bg border-left">
						<div class="col-lg-12">
								<!--
								{{ $_COOKIE['his-navbar'] }}
								-->
								@yield('content')
						</div>
					</div>
            </div>


        </div>
        </div>

		<script>

		@if (Session::has('message'))
				toastr.success(toastr.options,'{{ Session::get('message') }}')
		@endif

		function goBack() {
				window.history.back();
		}					

		@if (count($errors) > 0)
				toastr.error(toastr.options,'Please correct the errors highlighted below.')
		@endif

		function setBarState() {
				if (Cookies.get('his-navbar')==1) {
					Cookies.set('his-navbar',0, { expires: 7});
				} else {
					Cookies.set('his-navbar',1, { expires: 7});
				}
		}

		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
		</script>
</body>
