@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($consultation_diagnosis, ['route'=>['consultation_diagnoses.update',$consultation_diagnosis->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_diagnoses.consultation_diagnosis')
{{ Form::close() }}

@endsection
