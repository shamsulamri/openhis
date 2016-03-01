@extends('layouts.app')

@section('content')
<h1>
New Form Position
</h1>
@include('common.errors')
<br>
{{ Form::model($form_position, ['url'=>'form_positions', 'class'=>'form-horizontal']) }} 
    
	@include('form_positions.form_position')
{{ Form::close() }}

@endsection
