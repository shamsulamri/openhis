@extends('layouts.app')

@section('content')
<h1>
Edit Drug Prescription
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_prescription, ['route'=>['drug_prescriptions.update',$drug_prescription->prescription_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('drug_prescriptions.drug_prescription')
{{ Form::close() }}

@endsection
