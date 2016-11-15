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
		<link href="/assets/inspinia/css/style.css" rel="stylesheet">
		<script src="/assets/js/moment.min.2.5.0.js"></script>
		<script src="/assets/js/combodate.js"></script>
</head>
<body class='top-navigation'>
<div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
    	<nav class="navbar navbar-static-top" role='navigation'>
            <div class="navbar-header">
				<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <i class="fa fa-reorder"></i>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
					{{ Config::get('host.application_name') }}
                </a>
            </div>

			<div class="navbar-collapse collapse" id="navbar">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                        <li><a href="{{ url('/register') }}">Register</a></li>
                </ul>
            </div>
   		 </nav>
		</div>
		@yield('content')
</div>


    <!-- Mainly scripts -->
    <script src="/assets/inspinia/js/jquery-2.1.1.js"></script>
    <script src="/assets/inspinia/js/bootstrap.min.js"></script>
    <script src="/assets/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/assets/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/assets/inspinia/js/inspinia.js"></script>
    <script src="/assets/inspinia/js/plugins/pace/pace.min.js"></script>

    <!-- Flot -->
    <script src="/assets/inspinia/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/inspinia/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/inspinia/js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- ChartJS-->
    <script src="/assets/inspinia/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Peity -->
    <script src="/assets/inspinia/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="/assets/inspinia/js/demo/peity-demo.js"></script>
</body>
</html>
