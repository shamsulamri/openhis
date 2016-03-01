@extends('layouts.app')

@section('content')
<h1>
New Consultation Diagnosis
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_diagnosis, ['url'=>'consultation_diagnoses', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_diagnoses.consultation_diagnosis')
{{ Form::close() }}

@endsection
