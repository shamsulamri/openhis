@extends('layouts.app')

@section('content')
<h1>
New Patient Billing
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_billing, ['url'=>'patient_billings', 'class'=>'form-horizontal']) }} 
    
	@include('patient_billings.patient_billing')
{{ Form::close() }}

@endsection
