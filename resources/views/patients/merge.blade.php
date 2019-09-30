
@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Merge Patient Record
</h1>
<h2 class="text-danger"><i class="fa fa-warning"></i>&nbsp;Warning this process is irreversible.</h2>
<br>
{{ Form::open(['url'=>'patient/merge/'.$patient->patient_id]) }}
@if (!$duplicate_patient)
		<h4>Enter the duplicate identification to merge</h4>
		{{ Form::text('duplicate_id', $duplicate_id, ['class'=>'form-control','placeholder'=>'Patient MRN','maxlength'=>'100']) }}
		<br>
@endif
@if ($duplicate_patient)
<h3>Are you sure you want to merge: </h3>
<div class="widget style1 gray-bg">
<h4>{{ $duplicate_patient->patient_name }}</h4>
<h4>{{ $duplicate_patient->getMRN() }}</h4>
<h5>Identification: {{ $duplicate_patient->patientIdentification() }}</h5>
</div>
<h3>to:</h3>
<div class="widget style1 gray-bg">
<h4>{{ $patient->patient_name }}</h4>
<h4>{{ $patient->getMRN() }}</h4>
<h5>Identification: {{ $patient->patientIdentification() }}</h5>
</div>
<br>
@endif
		@if (!$duplicate_patient)
		{{ Form::submit('Find', ['class'=>'btn btn-primary']) }}

		@else
		<a class="btn btn-default" href="/patient/merge/{{ $patient->patient_id }}" role="button">Cancel</a>
		{{ Form::submit('Merge Now', ['class'=>'btn btn-primary']) }}
		{{ Form::hidden('target_id', $duplicate_patient->patient_id) }}
		@endif

		{{ Form::hidden('origin', $patient->patient_id) }}
{{ Form::close() }}
@endsection
