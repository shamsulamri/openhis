@extends('layouts.app')

@section('content')
<h1>
New Drug Disease
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_disease, ['url'=>'drug_diseases', 'class'=>'form-horizontal']) }} 
    
	@include('drug_diseases.drug_disease')
{{ Form::close() }}

@endsection
