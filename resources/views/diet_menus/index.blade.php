@extends('layouts.app2')

@section('content')
<h3>{{ $class->diet->diet_name }} / {{ $class->class_name }} / {{ $period->period_name }}</h3>
<br>

<table class="table table-condensed">
@foreach ($menu_products as $menu)
	<tr>
			<td>
					{{ $menu->product->product_name }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_menus/delete/'. $menu->menu_id) }}'>-</a>
			</td>
	</tr>
@endforeach
</table>
@endsection
