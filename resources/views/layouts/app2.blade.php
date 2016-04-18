<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <title>{{ Config::get('host.application_name') }}</title>
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="/assets/js/moment.min.2.5.0.js"></script>
		<script src="/assets/js/combodate.js"></script>
</head>
<body id="app-layout">
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
