@extends('layouts.app')

@section('content')
<h1>
Edit Stock Movement
</h1>
@include('common.errors')
<br>
{{ Form::model($inventory_movement, ['route'=>['inventory_movements.update',$inventory_movement->move_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('inventory_movements.inventory_movement')
{{ Form::close() }}

@endsection
