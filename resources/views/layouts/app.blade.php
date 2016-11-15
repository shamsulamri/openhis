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

<!-- Mainly scripts -->
<script src="/assets/inspinia/js/jquery-2.1.1.js"></script>
<script src="/assets/inspinia/js/bootstrap.min.js"></script>
<script src="/assets/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/assets/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/assets/inspinia/js/inspinia.js"></script>

<!-- Data picker -->
<script src="/assets/inspinia/js/plugins/datapicker/bootstrap-datepicker.js"></script>


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

		<script src="/assets/js/moment.min.2.5.0.js"></script>
		<script src="/assets/js/combodate.js"></script>
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

<body class="full-height-layout">

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
                       MSU 
                    </div>
                </li>
				@can('module-patient')
				<li><a href="{{ url('/patients') }}"><i class="fa fa-user"></i><span class='nav-label'>Patients</span></a></li>
				<li><a href="{{ url('/appointments') }}"><i class="fa fa-calendar"></i><span class='nav-label'>Appointments</span></a></li>
				<li><a href="{{ url('/queues') }}"><i class="fa fa-th-list"></i><span class='nav-label'>Queues</span></a></li>
				<li><a href="{{ url('/admissions') }}"><i class="glyphicon glyphicon-bed"></i><span class='nav-label'>Admissions</span></a></li>
				<li><a href="{{ url('/bed_bookings?type=preadmission') }}"><i class="glyphicon glyphicon-time"></i><span class='nav-label'>Preadmissions</span></a></li>
				<li><a href="{{ url('/discharges') }}"><i class="fa fa-home"></i><span class='nav-label'>Discharges</span></a></li>
				@endcan
            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="white-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
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

		function goBack() {
				window.history.back();
		}					

		@if (count($errors) > 0)
				toastr.error(toastr.options,'Please correct the errors highlighted below.')
		@endif
		</script>
</body>
