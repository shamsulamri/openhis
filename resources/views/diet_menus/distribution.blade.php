@extends('layouts.app')

@section('content')
<style>
table {
   border: none;
}
th,td {
   text-align: center;
}
</style>
<h1>Diet Distribution</h1>
<br>
<form class='form-horizontal' action='/diet_distribution' method='post' name='myform'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Diet</div></label>
						<div class='col-sm-9'>
							{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Class</label>
						<div class='col-sm-9'>
							{{ Form::select('class_code', $diet_classes, $class_code, ['class'=>'form-control','onchange'=>'reload()']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Period</label>
						<div class='col-sm-9'>
							{{ Form::select('period_code', $diet_periods, $period_code, ['class'=>'form-control','onchange'=>'reload()']) }}
						</div>
					</div>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

<a class='btn btn-primary pull-right'  target="_blank" href='{{ Config::get('host.report_server') }}/ReportServlet?report=meal_label'>
<span class='fa fa-print' aria-hidden='true'></span>
</a>

<br>
<br>

<table class='table table-bordered'>
	<thead>
	<tr>
		<th width='15%'>Menu</th>
		@foreach ($wards as $ward)
		<th width='15%'>{{ $ward->ward_name }}</th>
		@endforeach
		<th width='15%'>Total</th>
	</tr>
	</thead>
	@foreach ($menu_products as $product)
	<tr>
		<td class='info' width='15%'>
			<div align='left'>
			{{ $product->product_name }}
			</div>
		</td>
		<?php $total=0; ?>
		@foreach ($wards as $ward)
		<?php $count=$dietHelper->distribution($diet_code, $class_code, $product->period_code, $product->product_code, $ward->ward_code); ?>
		<td width='15%'>{{ $count }}</td>
		<?php $total+=$count; ?>
		@endforeach
		<td><strong>{{ $total }}</strong></td>
	</tr>
	@endforeach
</table>
<script>
	function reload() {
			document.myform.submit();
	}
</script>
@endsection
