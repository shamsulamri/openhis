@extends('layouts.app')

@section('content')
@include('consultations.panel')


{{ Form::model($consultation_procedure, ['url'=>'consultation_procedures', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_procedures.consultation_procedure')
{{ Form::close() }}

@endsection
