@extends('layouts.app')

@section('content')
<style>
table, th, td {
   border: none;
}
th {
   text-align: center;
}
</style>
<h1>Bill of Materials</h1>
<br>
<form class='form-inline' action='/diet_bom' method='post' name='myform'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

<br>

<table class='table table-bordered'>
	<tr>
		<th width='15%'>Period</th>
		<th width='15%'>Menu</th>
		<th width='15%'>Unit</th>
		<th width='30%'>BoM</th>
	</tr>
	@foreach ($menu_products as $product)
	<tr>
		<td>{{ $product->period_name }}</td>
		<td width='15%'>{{ $product->product_name }}</td>
		<?php $total=0; ?>
		@foreach ($diet_classes as $class)
		<?php $count=$dietHelper->cooklist($diet_code,$class->class_code, $product->period_code, $product->product_code); ?>
		<?php $total+=$count; ?>
		@endforeach
		<td>
			<div align='center'>
				{{ $total }}
			</div>
		</td>
		<?php $boms =$dietHelper->bom($product->product_code) ?> 
		<td>
		<table width='100%'>
		@foreach ($boms as $bom)
		<tr>
			<td>
			{{ $bom->product->product_name }}
			</td>
			<td>
			<div align='right'>
			{{ $bom->bom_quantity*$total }}
			@if (!empty($bom->product->unitMeasure->unit_shortname))
			{{ $bom->product->unitMeasure->unit_shortname }}
			@else
			unit
			@endif
			</div>
			</td>
		</tr>
		@endforeach	
		</table>
		</td>

	</tr>
	@endforeach
</table>
<script>
	function reload() {
			document.myform.submit();
	}
</script>
@endsection
