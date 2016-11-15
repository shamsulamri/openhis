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
<body>
                    <div class="full-height-scroll white-bg">
						<div class="col-lg-12">
								<br>
								@yield('content')
						</div>
					</div>
		<script>
		@if (Session::has('message'))
				toastr.success(toastr.options,'{{ Session::get('message') }}')
		@endif
		</script>
</body>
