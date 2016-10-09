@extends('layouts.app')

@section('content')
<h1>
New Gender
</h1>
@include('common.errors')
<br>
{{ Form::model($gender, ['url'=>'genders', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('gender_code')) has-error @endif'>
        <label for='gender_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('gender_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'1']) }}
            @if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
        </div>
    </div>    
    
	@include('genders.gender')
{{ Form::close() }}

@endsection
