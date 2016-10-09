@extends('layouts.app')

@section('content')
<h1>
New Patient Flag
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_flag, ['url'=>'patient_flags', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('flag_code')) has-error @endif'>
        <label for='flag_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('flag_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('flag_code')) <p class="help-block">{{ $errors->first('flag_code') }}</p> @endif
        </div>
    </div>    
    
	@include('patient_flags.patient_flag')
{{ Form::close() }}

@endsection
