@extends('layouts.app')

@section('content')
<h1>
Edit Drug Disease
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_disease, ['route'=>['drug_diseases.update',$drug_disease->disease_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('drug_diseases.drug_disease')
{{ Form::close() }}

@endsection
