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
<h1>Diet Work Order</h1>
<br>
<form class='form-inline' action='/diet_workorder' name='myform' method='post'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<label>Period&nbsp;</label>
	{{ Form::select('period_code', $diet_periods, $period_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

@foreach ($menu_products as $product)
		<?php $grand_total=0; ?>
		<h3>{{ $product->product_name }}</h3>
		<table class='table table-bordered table-condensed'>
		<tr>
			<th>Ward</th>
		@foreach ($diet_classes as $class)
			<th width='15%'>	
			{{ $class->class_name }}
			</th>
		@endforeach
			<th>Total</th>
		</tr>
		@foreach ($wards as $ward)
		<tr>
			<td>	
			<div align='left'>
			{{ $ward->ward_name }}
			</div>
			</td>
				<?php $total=0; ?>
				@foreach ($diet_classes as $class)
				<td>	
					<?php 
						$count=$dietHelper->workorder($diet_code,$class->class_code, $product->period_code, $product->product_code, $ward->ward_code); 
						$total+=$count;
						$grand_total += $count;
					?>
					
					{{ $count }}
				</td>
				@endforeach
				<td>{{ $total }}</td>
		</tr>
		@endforeach
	<tr>
		<td colspan='{{ count($diet_classes) }}'>
		<td>Grand Total</td>
		<td>{{ $grand_total }}</td>
	</tr>
		</table>
@endforeach

<script>
	function reload() {
			document.myform.submit();
	}
</script>

@endsection
