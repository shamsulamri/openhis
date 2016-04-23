@extends('layouts.app')

@section('content')
<h1><a href='/products'>Product Index</a> / Drug Prescription</h1>
<br>
@include('products.id')
@include('common.errors')
<br>
{{ Form::model($drug_prescription, ['route'=>['drug_prescriptions.update',$drug_prescription->prescription_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('drug_prescriptions.drug_prescription')
{{ Form::close() }}

@endsection
