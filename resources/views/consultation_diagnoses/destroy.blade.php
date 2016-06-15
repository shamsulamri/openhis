@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')
<h1>Delete Diagnosis</h1>
<br>
<h4>
Are you sure you want to delete the selected record ?
{{ $consultation_diagnosis->diagnosis_clinical }}
{{ Form::open(['url'=>'consultation_diagnoses/'.$consultation_diagnosis->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/consultation_diagnoses" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
