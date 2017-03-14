@extends('layouts.app2')

@section('content')
{{ Form::model($form_position, ['route'=>['form_positions.update',$form_position->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('form_positions.form_position')
{{ Form::close() }}

@endsection
