@extends('layouts.app')

@section('content')
@include('consultations.panel')


{{ Form::model($newborn, ['url'=>'newborns', 'class'=>'form-horizontal','onsubmit'=>'submitButton.disabled = true; return true;']) }} 
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
