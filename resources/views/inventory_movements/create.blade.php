@extends('layouts.app')

@section('content')
<h1>
New Stock Movement
</h1>
@include('common.errors')
<br>
{{ Form::model($inventory_movement, ['url'=>'inventory_movements', 'class'=>'form-horizontal']) }} 
    
	@include('inventory_movements.inventory_movement')
{{ Form::close() }}

@endsection
