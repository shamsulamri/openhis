@extends('layouts.app')

@section('content')
<h1>
New Drug Prescription
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_prescription, ['url'=>'drug_prescriptions', 'class'=>'form-horizontal']) }} 
    
	@include('drug_prescriptions.drug_prescription')
{{ Form::close() }}

@endsection
