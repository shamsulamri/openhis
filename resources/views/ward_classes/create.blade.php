@extends('layouts.app')

@section('content')
<h1>
New Ward Class
</h1>
@include('common.errors')
<br>
{{ Form::model($ward_class, ['url'=>'ward_classes', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('class_code')) has-error @endif'>
        <label for='class_code' class='col-sm-3 control-label'>Class<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('class_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>    
    
	@include('ward_classes.ward_class')
{{ Form::close() }}

@endsection
