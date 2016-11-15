@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Patient Demography
</h1>

{{ Form::model($patient, ['route'=>['patients.update',$patient->patient_id],'method'=>'PUT', 'class'=>'form-horizontal','enctype'=>'multipart/form-data']) }} 
	@include('patients.patient')
{{ Form::close() }}

@endsection
