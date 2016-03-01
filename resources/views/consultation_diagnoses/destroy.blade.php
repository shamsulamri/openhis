@extends('layouts.app')

@section('content')
<h1>
Delete Consultation Diagnosis
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $consultation_diagnosis->diagnosis_clinical }}
{{ Form::open(['url'=>'consultation_diagnoses/'.$consultation_diagnosis->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/consultation_diagnoses" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
