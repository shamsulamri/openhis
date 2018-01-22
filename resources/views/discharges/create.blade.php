@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Discharge</h1>
<br>
@if ($discharge->encounter->encounter_code != 'mortuary')
		@if ($fees==0)
			<div class='alert alert-danger' role='alert'>
			<p>
			Please enter your consultation fee in the order section.
			</p>
			</div>
		@endif
@endif

{{ Form::model($discharge, ['url'=>'discharges', 'class'=>'form-horizontal']) }} 
	@include('discharges.discharge')
{{ Form::close() }}

@endsection
