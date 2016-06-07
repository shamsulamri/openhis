@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
<br>
@include('common.errors')
{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
