@extends('layouts.app')

@section('content')
<h1>
New Patient
</h1>
@include('common.errors')
<h3>
Enter minimal patient information
</h3>
<br>
{{ Form::model($patient, ['url'=>'patients', 'class'=>'form-horizontal']) }} 
	<div class='form-group  @if ($errors->has('patient_name')) has-error @endif'>
        <label for='patient_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('patient_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('patient_name')) <p class="help-block">{{ $errors->first('patient_name') }}</p> @endif
        </div>
    </div>
	
	<div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
        <label for='gender_code' class='col-sm-3 control-label'>Gender<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('gender_code', $gender,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('patient_age')) has-error @endif'>
        <label for='patient_age' class='col-sm-3 control-label'>Estimated Age<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('patient_age', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_age')) <p class="help-block">{{ $errors->first('patient_age') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/patients" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

{{ Form::close() }}

@endsection
