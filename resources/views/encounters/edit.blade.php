@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')

{{ Form::model($encounter, ['route'=>['encounters.update',$encounter->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
