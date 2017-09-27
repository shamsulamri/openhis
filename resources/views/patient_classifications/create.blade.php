@extends('layouts.app')

@section('content')
<h1>
New Patient Classification
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_classification, ['url'=>'patient_classifications', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('classification_code')) has-error @endif'>
        <label for='classification_code' class='col-sm-2 control-label'>classification_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('classification_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('classification_code')) <p class="help-block">{{ $errors->first('classification_code') }}</p> @endif
        </div>
    </div>    
    
	@include('patient_classifications.patient_classification')
{{ Form::close() }}

@endsection
