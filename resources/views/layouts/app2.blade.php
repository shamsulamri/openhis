<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ env('APPLICATION_NAME') }}</title>
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

		@if (Session::has('warning'))
				toastr.options={"positionClass": "toast-top-full-width"};
				toastr.warning(toastr.options,'{{ Session::get('warning') }}')
		@endif

		@if (Session::has('error'))
				toastr.error(toastr.options,'{{ Session::get('error') }}')
		@endif

		@if (Session::has('info'))
				toastr.info(toastr.options,'{{ Session::get('info') }}')
		@endif
		@if (count($errors) > 0)
				toastr.error(toastr.options,'Please correct the errors highlighted below.')
		@endif

		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});

		$(document).ready(function() {
				$("input:text").focus(function() { $(this).select(); } );
		});

		</script>
</body>
