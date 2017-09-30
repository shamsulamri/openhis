
@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Merge Patient Record
</h1>
<br>
{{ Form::open(['url'=>'patient/merge/'.$patient->patient_id]) }}
@if (!$duplicate_patient)
		<h4>Enter the duplicate identification to merge</h4>
		{{ Form::text('duplicate_id', $duplicate_id, ['class'=>'form-control','placeholder'=>'Patient ID','maxlength'=>'100']) }}
		<br>
@endif
@if ($duplicate_patient)
<h3>Are you sure you want to merge the duplicate record ?</h3>
<br>
<h3>{{ $duplicate_patient->patient_name }}</h3>
<h3>{{ $duplicate_patient->getMRN() }}</h3>
<br>
@endif
		@if (!$duplicate_patient)
		{{ Form::submit('Find', ['class'=>'btn btn-primary']) }}

		@else
		<a class="btn btn-default" href="/patient/merge/{{ $patient->patient_id }}" role="button">Cancel</a>
		{{ Form::submit('Merge', ['class'=>'btn btn-primary']) }}
		{{ Form::hidden('target_id', $duplicate_patient->patient_id) }}
		@endif

		{{ Form::hidden('origin', $patient->patient_id) }}
{{ Form::close() }}
@endsection
