@extends('layouts.app')

@section('content')
<h1>
New Patient Mrn
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_mrn, ['url'=>'patient_mrns', 'class'=>'form-horizontal']) }} 
    
	@include('patient_mrns.patient_mrn')
{{ Form::close() }}

@endsection
