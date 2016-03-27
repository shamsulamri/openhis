@extends('layouts.app')

@section('content')
@include('patients.label')
<h2>
New Newborn 
</h2>
@include('common.errors')
<br>
{{ Form::model($newborn, ['url'=>'newborns', 'class'=>'form-horizontal']) }} 
    
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
