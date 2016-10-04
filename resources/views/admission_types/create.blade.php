@extends('layouts.app')

@section('content')
<h1>
New Admission Type
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_type, ['url'=>'admission_types', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('admission_code')) has-error @endif'>
        <label for='admission_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('admission_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10']) }}
            @if ($errors->has('admission_code')) <p class="help-block">{{ $errors->first('admission_code') }}</p> @endif
        </div>
    </div>    
    
	@include('admission_types.admission_type')
{{ Form::close() }}

@endsection
