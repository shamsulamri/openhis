@extends('layouts.app')

@section('content')
@include('patients.id')
<div class='page-header'>
		<h2>New Encounter</h2>
</div>
@include('common.errors')

{{ Form::model($encounter, ['url'=>'encounters', 'class'=>'form-horizontal']) }} 
	@include('encounters.encounter')
{{ Form::close() }}

@endsection
