@extends('layouts.app')

@section('content')
@include('patients.id_only')
<?php
$consultation = $wardHelper->hasOpenConsultation($admission->encounter->patient_id, $admission->encounter_id, Auth::user()->id);
?>

@if (empty($consultation))
		<h1>
		Start Consultation
		</h1>
		<br>
		<h3>
		Are you sure you want to start a new consultation ?
		<div class='pull-right'>
		<a class="btn btn-default" href="/admissions" role="button">Cancel</a>
		<a class='btn btn-primary' href='{{ URL::to('consultations/create?encounter_id='. $admission->encounter_id) }}'>Start</a>
		</div>
		</h3>
@else
		<h1>
		Resume Consultation
		</h1>
		<br>
		<h3>
		You have a pending consultation with this patient. Click Resume button to continue.
		<div class='pull-right'>
		<a class='btn btn-primary' href='{{ URL::to('consultations/'. $wardHelper->openConsultationId. '/edit') }}'>Resume</a>
		</div>
		</h3>
@endif
@endsection
