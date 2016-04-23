@extends('layouts.app')

@section('content')
<h1>
Edit Patient Dependant
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_dependant, ['route'=>['patient_dependants.update',$patient_dependant->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('patient_dependants.patient_dependant')
{{ Form::close() }}

@endsection
