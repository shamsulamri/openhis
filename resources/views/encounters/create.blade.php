@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')
{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
