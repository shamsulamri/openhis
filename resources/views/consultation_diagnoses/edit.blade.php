@extends('layouts.app')

@section('content')
<h1>
Edit Consultation Diagnosis
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_diagnosis, ['route'=>['consultation_diagnoses.update',$consultation_diagnosis->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_diagnoses.consultation_diagnosis')
{{ Form::close() }}

@endsection
