@extends('layouts.app')

@section('content')
@include('patients.id')
<h2>New Encounter</h2>
<br>
@include('common.errors')

{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
