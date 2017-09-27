@extends('layouts.app')

@section('content')
<h1>
Edit Patient Classification
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_classification, ['route'=>['patient_classifications.update',$patient_classification->classification_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>classification_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('classification_code', $patient_classification->classification_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('patient_classifications.patient_classification')
{{ Form::close() }}

@endsection
