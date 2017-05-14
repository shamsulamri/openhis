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
<link href="/assets/inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">



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

<!-- Jquery Validate -->
<script src="/assets/inspinia/js/plugins/validate/jquery.validate.min.js"></script>

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
<body class="mini-navbar">
<?php } else { ?>
<body>
<!-- full-height-layout -->
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
							$initial2="";
							if(!empty($initials[1])) { 
									$initial2 = substr(trim($initials[1]),0,1); 
							}
						?>
						<div title='{{ Auth::user()->name }}'>{{ $initials[0] }}{{ $initial2 }}</div>
                    </div>
                </li>
				@can('system-administrator')
					<li><a title="Maintenance" href="{{ url('/maintenance') }}"><i class='glyphicon glyphicon-cog'></i><span class='nav-label'>Maintenance</span></a></li>
				@endcan
						<div class="dropdown profile-element"> 
							<br>
						</div>
				@cannot('system-administrator')
						<!-- Support -->
						@can('module-support')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Support</h4>
						</div>
						<li><a href="{{ url('/order_queues') }}"><i class='fa fa-question-circle-o'></i><span class='nav-label'>Outpatient Tasks</span></a></li>
						<li><a href="{{ url('/admission_tasks') }}"><i class='fa fa-question-circle'></i><span class='nav-label'>Inpatient Tasks<span></a></li>
						<li><a href="{{ url('/order_queues?discharge=true') }}"><i class='fa fa-user-md'></i><span class='nav-label'>Future Orders<span></a></li>

						<!-- Patient Module -->
						@endcan
						@can('module-patient')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Patient</h4>
						</div>
						<li><a title='Patient List' href="{{ url('/patients') }}" title='Patients'><i class="fa fa-user"></i><span class='nav-label'>Patients</span></a></li>
						<li><a title='Appointments' href="{{ url('/appointments') }}"><i class="fa fa-calendar"></i><span class='nav-label'>Appointments</span></a></li>
						<li><a title='Future Orders' href="{{ url('/futures') }}"><i class="fa fa-user-md"></i><span class='nav-label'>Future Orders</span></a></li>
						<li><a title='Queues' href="{{ url('/queues') }}"><i class="fa fa-th-list"></i><span class='nav-label'>Queues</span></a></li>
						<li><a title='Admissions' href="{{ url('/admissions') }}"><i class="fa fa-users"></i><span class='nav-label'>Admissions</span></a></li>
						<li><a title='Preadmissions' href="{{ url('/bed_bookings?type=preadmission') }}"><i class="glyphicon glyphicon-time"></i><span class='nav-label'>Preadmissions</span></a></li>
						<li><a title='Discharges' href="{{ url('/discharges') }}"><i class="fa fa-home"></i><span class='nav-label'>Discharges</span></a></li>
						<li><a title='Beds' href="{{ url('/beds') }}"><i class="glyphicon glyphicon-bed"></i><span class='nav-label'>Beds</span></a></li>
						<li><a title='Loans' href="{{ url('/loans?type=folder') }}"><i class='glyphicon glyphicon-transfer'></i><span class='nav-label'><span class='nav-label'>Loans</span></a></li>
						@endcan
						<!-- Consultation Module -->
						@can('module-consultation')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Consultation</h4>
						</div>
						<li><a title='Patient List' href="/patient_lists"><i class="fa fa-stethoscope"></i><span class='nav-label'>Patient List</span></a></li>
						<li><a title='Consultation List' href="/consultations"><i class="fa fa-comments-o"></i><span class='nav-label'>Consultation List</span></a></li>
						<li><a title='Appointments' href="{{ url('/appointments') }}"><i class="fa fa-calendar"></i><span class='nav-label'>Appointments</span></a></li>
						<h4>&nbsp;</h4>
						@if (!empty($consultation) && !empty($patient))
							<li><a title='Forms' href='{{ URL::to('form/results',$consultation->encounter->encounter_id) }}'><i class='fa fa-table'></i><span class='nav-label'>Forms</span></a></li>
							<li><a title='Medical Alerts' href="/medical_alerts"><i class="fa fa-exclamation-circle"></i><span class='nav-label'>Medical Alerts</span></a></li>
							@if ($consultation->encounter->encounter_code=='inpatient')
							<li><a title='Diet' href="/diet"><i class="fa fa-cutlery"></i><span class='nav-label'>Diet</a></li>
							@endif
							@if ($patient->gender_code=='P')
							<li><a title='Obstetic History' href="/obstetric"><i class="fa fa-user"></i><span class='nav-label'>Obstetric History</span></a></li>
							<li><a title='Newborn Registration' href="/newborns"><i class="fa fa-gift"></i><span class='nav-label'>Newborn Registration</span></a></li>
							@endif
							<li><a title='Medical Certificate' href="/medical_certificates/create"><i class="fa fa-certificate"></i><span class='nav-label'>Medical Certificate</span></a></li>
							<li><a title='Medical Documents' href="/documents?patient_mrn={{ $patient->patient_mrn }}"><i class="fa fa-files-o"></i><span class='nav-label'>Documents</span></a></li>
						@endif
						@endcan

						<!-- Diet Module -->
						@can('module-diet')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Diet</h4>
						</div>
						<li><a title='Diet Orders' href="{{ url('/diet_orders') }}"><i class='glyphicon glyphicon-asterisk'></i><span class='nav-label'>Diet Orders</span></a></li>
						<li><a title='Diet Menus' href="{{ url('/diet_menus') }}"><i class='glyphicon glyphicon-cutlery'></i><span class='nav-label'>Diet Menus</span></a></li>
						<li><a title='Diet Cooklist' href="{{ url('/diet_cooklist') }}"><i class='glyphicon glyphicon-file'></i><span class='nav-label'>Diet Cooklist</span></a></li>
						<li><a title='Diet BOM' href="{{ url('/diet_bom') }}"><i class='glyphicon glyphicon-th-large'></i><span class='nav-label'>Diet Bill of Materials</span></a></li>
						<li><a title='Diet Work Order' href="{{ url('/diet_workorder') }}"><i class='glyphicon glyphicon-ok-sign'></i><span class='nav-label'>Diet Work Order</span></a></li>
						<li><a title='Diet Distribution' href="{{ url('/diet_distribution') }}"><i class='glyphicon glyphicon-random'></i><span class='nav-label'>Diet Distribution</span></a></li>
						<li><a title='Diet Complains' href="{{ url('/diet_complains') }}"><i class='glyphicon glyphicon-thumbs-down'></i><span class='nav-label'>Diet Complains</span></a></li>
						<li><a title='Diet Wastages' href="{{ url('/diet_wastages') }}"><i class='glyphicon glyphicon-trash'></i><span class='nav-label'>Diet Wastages</span></a></li>
						<li><a title='Diet Qualities' href="{{ url('/diet_qualities') }}"><i class='glyphicon glyphicon-star'></i><span class='nav-label'>Diet Qualities</span></a></li>
						<h4>&nbsp;</h4>
						@endcan

						<!-- Inventory Module -->
						@can('module-inventory')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Inventory</h4>
						</div>
						<li><a title='Products' href="{{ url('/products') }}"><i class='fa fa-glass'></i><span class='nav-label'>Products</span></a></li>
						<li><a title='Purchase Orders' href="{{ url('/purchase_orders') }}"><i class='fa fa-shopping-cart'></i><span class='nav-label'>Purchase Orders</span></a></li>
						<li><a title='Suppliers' href="{{ url('/suppliers') }}"><i class='fa fa-truck' aria-hidden='true'></i><span class='nav-label'>Suppliers</span></a></li>
						<li><a title='Stores' href="{{ url('/stores') }}"><i class='fa fa-th-large'></i><span class='nav-label'>Stores</span></a></li>
						<li><a title='Order Sets' href="{{ url('/sets') }}"><i class='fa fa-medkit'></i><span class='nav-label'>Order Sets</span></a></li>
						<li><a title='Product Authorizations' href="{{ url('/product_authorizations') }}"><i class='fa fa-barcode'></i><span class='nav-label'>Product Authorizations</span></a></li>
						<li><a title='Loans' href="{{ url('/loans') }}"><i class='glyphicon glyphicon-transfer'></i><span class='nav-label'><span class='nav-label'>Loans</span></a></li>
						@endcan

						<!-- Ward Module -->
						@can('module-ward')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Ward</h4>
						</div>
						<li><a title="Admissions" href="{{ url('/admissions') }}"><i class='fa fa-users'></i><span class='nav-label'>Admissions</a></li>
						<li><a title="Inpatient Tasks" href="{{ url('/admission_tasks') }}"><i class='fa fa-question-circle'></i><span class='nav-label'>Admission Tasks</a></li>
						@if (!empty($ward->ward_code))
								@if ($ward->ward_code != 'mortuary')
								<!--
								<li><a title="Patients" href="{{ url('/patients') }}"><i class='fa fa-user'></i><span class='nav-label'>Patient List</a></li>
								<li><a title="Appointments" href="{{ url('/appointments') }}"><i class='fa fa-calendar'></i><span class='nav-label'>Appointments</a></li>
								-->
								@endif
						@endif
						<li><a title='Beds' href="{{ url('/beds') }}"><i class="glyphicon glyphicon-bed"></i><span class='nav-label'>Beds</span></a></li>
						<li><a title="Bed Reservations" href="{{ url('/bed_bookings') }}"><i class='glyphicon glyphicon-bookmark'></i><span class='nav-label'>Bed Reservations</a></li>
						<li><a title="Appointments" href="{{ url('/appointments') }}"><i class='fa fa-calendar'></i><span class='nav-label'>Appointments</a></li>
						<li><a title="Loans" href="{{ url('/loans/ward') }}"><i class='glyphicon glyphicon-transfer'></i><span class='nav-label'>Loans</a></li>
						@endcan

						<!-- Medical Record -->
						@can('module-medical-record')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Medical Record</h4>
						</div>
						<li><a title="Patient List" href="{{ url('/patients') }}"><i class='fa fa-user'></i><span class='nav-label'>Patient List</a></li>
						<li><a title="Loans" href="{{ url('/loans?type=folder') }}"><i class='glyphicon glyphicon-transfer'></i><span class='nav-label'>Loans</a></li>
						@endcan

						<!-- Financial Module -->
						@can('module-discharge')
						<div class="dropdown profile-element"> 
								<h4>&nbsp;Financial</h4>
						</div>
						<li><a title='Patient List' href="{{ url('/patients') }}"><i class='fa fa-user'></i><span class='nav-label'>Patients</span></a></li>
						<li><a title='Discharges' href="{{ url('/discharges') }}"><i class='fa fa-home'></i><span class='nav-label'>Discharges</span></a></li>
						<li><a title="Admissions" href="{{ url('/admissions') }}"><i class='fa fa-users'></i><span class='nav-label'>Admissions</a></li>
						@endcan

						<!-- Product List -->
						@can('product_list')
								@cannot('module-inventory')
								<li><a title="Products" href="{{ url('/products') }}"><i class='glyphicon glyphicon-glass'></i><span class='nav-label'>Products</a></li>
								@endcannot
						@endcan

						<!-- Report -->
						<li><a href="{{ url('/reports') }}"><i class="fa fa-bar-chart"></i><span class='nav-label'>Reports</span></a></li>
				@endcan
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

		@if (Session::has('warning'))
				toastr.options={"positionClass": "toast-top-full-width"};
				toastr.warning(toastr.options,'{{ Session::get('warning') }}')
		@endif

		@if (Session::has('info'))
				toastr.info(toastr.options,'{{ Session::get('info') }}')
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
