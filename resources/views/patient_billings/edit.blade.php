@extends('layouts.app')

@section('content')
<h1>
Edit Patient Billing
</h1>

<br>
{{ Form::model($patient_billing, ['route'=>['patient_billings.update',$patient_billing->bill_id],'method'=>'PUT', 'class'=>'form-horizontal','enctype'=>'multipart/form-data']) }} 
    
	@include('patient_billings.patient_billing')
{{ Form::close() }}

@endsection
