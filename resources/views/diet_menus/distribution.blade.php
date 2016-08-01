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
<form class='form-inline' action='/diet_distribution' method='post' name='myform'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<label>Class&nbsp;</label>
	{{ Form::select('class_code', $diet_classes, $class_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<label>Period&nbsp;</label>
	{{ Form::select('period_code', $diet_periods, $period_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

<br>

<table class='table table-bordered'>
	<tr>
		<th width='15%'>Menu</th>
		@foreach ($wards as $ward)
		<th width='15%'>{{ $ward->ward_name }}</th>
		@endforeach
		<th width='15%'>Total</th>
	</tr>
	@foreach ($menu_products as $product)
	<tr>
		<td width='15%'>
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
		<td>{{ $total }}</td>
	</tr>
	@endforeach
</table>
<script>
	function reload() {
			document.myform.submit();
	}
</script>
@endsection
