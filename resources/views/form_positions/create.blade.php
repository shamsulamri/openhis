@extends('layouts.app2')

@section('content')
<h1>
New Form Position
</h1>

<br>
{{ Form::model($form_position, ['url'=>'form_positions', 'class'=>'form-horizontal']) }} 
    
	@include('form_positions.form_position')
{{ Form::close() }}

@endsection
