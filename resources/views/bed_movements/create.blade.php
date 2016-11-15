@extends('layouts.app')

@section('content')
<h1>
New Bed Movement
</h1>

<br>
{{ Form::model($bed_movement, ['url'=>'bed_movements', 'class'=>'form-horizontal']) }} 
    
	@include('bed_movements.bed_movement')
{{ Form::close() }}

@endsection
