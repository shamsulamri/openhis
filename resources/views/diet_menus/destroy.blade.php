@extends('layouts.app2')

@section('content')
<h3>
Delete Menu Item
</h3>

<br>
<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $menu->product->product_name }}
<br>
{{ Form::open(['url'=>'diet_menus/delete/'.$menu->menu_id]) }}
	{{ method_field('DELETE') }}
	<br>
	<a class="btn btn-default" href="/diet_menus/menu/{{ $menu->class_code }}/{{ $menu->period_code}}/{{ $menu->week_index }}/{{ $menu->day_index }}/{{ $menu->diet_code }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
