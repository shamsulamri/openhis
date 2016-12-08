@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Drug Prescription</h1>

<br>
{{ Form::model($drug_prescription, ['id'=>'form','route'=>['drug_prescriptions.update',$drug_prescription->prescription_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('drug_prescriptions.drug_prescription')
{{ Form::close() }}

@endsection
