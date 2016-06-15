@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($consultation_procedure, ['route'=>['consultation_procedures.update',$consultation_procedure->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('consultation_procedures.consultation_procedure')
{{ Form::close() }}

@endsection
