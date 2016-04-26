@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Encounter</h1>
<br>
@include('common.errors')

{{ Form::model($encounter, ['route'=>['encounters.update',$encounter->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
