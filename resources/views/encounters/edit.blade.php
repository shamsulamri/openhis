@extends('layouts.app')

@section('content')
<h1>
Edit Encounter
</h1>
@include('common.errors')
<br>
{{ Form::model($encounter, ['route'=>['encounters.update',$encounter->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
