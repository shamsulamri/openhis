@extends('layouts.app')

@section('content')
@include('consultations.panel')


{{ Form::model($consultation_diagnosis, ['url'=>'consultation_diagnoses', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_diagnoses.consultation_diagnosis')
{{ Form::close() }}

@endsection
