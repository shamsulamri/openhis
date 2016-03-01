@extends('layouts.app')

@section('content')
<h1>
Edit Form Position
</h1>
@include('common.errors')
<br>
{{ Form::model($form_position, ['route'=>['form_positions.update',$form_position->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('form_positions.form_position')
{{ Form::close() }}

@endsection
