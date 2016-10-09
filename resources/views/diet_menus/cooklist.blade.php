@extends('layouts.app')

@section('content')
<style>
th, td {
   text-align: center;
}
</style>
<h1>Diet Cooklist</h1>
<br>
<form class='form-inline' action='/diet_cooklist' method='post' name='myform'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

<br>

<table class='table table-bordered'>
	<tr>
		<th width='15%'>Period</th>
		<th width='15%'>Menu</th>
		@foreach ($diet_classes as $class)
		<th width='15%'>{{ $class->class_name }}</th>
		@endforeach
		<th width='15%'>Total</th>
	</tr>
	@foreach ($menu_products as $product)
	<tr>
		<td><div align='left'>{{ $product->period_name }}</div></td>
		<td width='15%'><div align='left'>{{ $product->product_name }}</div></td>
		<?php $total=0; ?>
		@foreach ($diet_classes as $class)
		<?php $count=$dietHelper->cooklist($diet_code,$class->class_code, $product->period_code, $product->product_code); ?>
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
