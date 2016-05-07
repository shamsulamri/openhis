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
<body id="app-layout" background=''>
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
                    <li><a href="{{ url('/register') }}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>
	<div class='container'>
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
