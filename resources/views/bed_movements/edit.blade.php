@extends('layouts.app')

@section('content')
<h1>
Edit Bed Movement
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_movement, ['route'=>['bed_movements.update',$bed_movement->move_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bed_movements.bed_movement')
{{ Form::close() }}

@endsection
