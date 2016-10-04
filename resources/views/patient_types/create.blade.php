@extends('layouts.app')

@section('content')
<h1>
New Patient Type
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_type, ['url'=>'patient_types', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('type_code')) has-error @endif'>
        <label for='type_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('type_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('type_code')) <p class="help-block">{{ $errors->first('type_code') }}</p> @endif
        </div>
    </div>    
    
	@include('patient_types.patient_type')
{{ Form::close() }}

@endsection
