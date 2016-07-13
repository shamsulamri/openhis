@extends('layouts.app')

@section('content')
<h1>Diet Menu</h1>
<br>
<form class='form-inline' action='/diet_menu/search' method='post'>
	<label>Diet&nbsp;</label>
	{{ Form::select('diet_code', $diets, $diet_code, ['class'=>'form-control']) }}
	<!--
	<label>Class&nbsp;</label>
	{{ Form::select('class_code', $diet_classes, $class_code, ['class'=>'form-control']) }}
	-->
	<label>Week&nbsp;</label>
	{{ Form::select('weekOfMonth', $weeks, $weekOfMonth, ['class'=>'form-control']) }}
	<label>Day&nbsp;</label>
	{{ Form::select('dayOfWeek', $days, $dayOfWeek, ['class'=>'form-control']) }}
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


@endsection
