@extends('layouts.app')

@section('content')
<style>
th {
   text-align: center;
}
</style>
<h1>Diet Menu</h1>
<br>
<form class='form-inline' action='/diet_menus' name='myform' method='post'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control','onchange'=>'reload()']) }}
	<label>Week&nbsp;</label>
	{{ Form::select('weekOfMonth', $weeks, $weekOfMonth, ['class'=>'form-control','onchange'=>'reload()']) }}
	<label>Day&nbsp;</label>
	{{ Form::select('dayOfWeek', $days, $dayOfWeek, ['class'=>'form-control','onchange'=>'reload()']) }}
	{{ Form::hidden('refresh','1') }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">	
</form>

<br>
<table class='table table-bordered'>
	<tr>
		<th width='20%'>Class</th>
		@foreach ($diet_periods as $period)
		<th width='15%'>{{ $period->period_name }}</th>
		@endforeach
	</tr>
	@foreach ($diet_classes as $class)
	<tr width='300'>
		<td>
			{{ $class->class_name }}
		</td>
		@foreach ($diet_periods as $period)
		<td>
				<a href='/diet_menus/{{ $class->class_code }}/{{ $period->period_code }}/{{ $weekOfMonth }}/{{ $dayOfWeek }}' class='btn btn-default btn-xs'>+</a>
				<?php
				$products = $dietHelper->menus($class->class_code, $period->period_code, $weekOfMonth, $dayOfWeek);
				?>
				<br>
				@foreach ($products as $product)
						<small>
						{{ $product->product->product_name}}
						</small>	
						<br>
				@endforeach
		</td>
		@endforeach
	</tr>
	@endforeach
</table>

<script>
	function reload() {
			document.myform.submit();
	}
</script>

@endsection
