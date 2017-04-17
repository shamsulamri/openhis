@extends('layouts.app')

@section('content')
<h1>
New Form System
</h1>
@include('common.errors')
<br>
{{ Form::model($form_system, ['url'=>'form_systems', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('system_code')) has-error @endif'>
        <label for='system_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('system_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('system_code')) <p class="help-block">{{ $errors->first('system_code') }}</p> @endif
        </div>
    </div>    
    
	@include('form_systems.form_system')
{{ Form::close() }}

@endsection
