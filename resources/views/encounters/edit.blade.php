@extends('layouts.app')

@section('content')
@include('patients.id')
<div class='page-header'>
		<h2>Edit Encounter</h2>
</div>
@include('common.errors')

{{ Form::model($encounter, ['route'=>['encounters.update',$encounter->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
