@extends('layouts.app')

@section('content')
<h1>
New Form Property
</h1>
@include('common.errors')
<br>
{{ Form::model($form_property, ['url'=>'form_properties', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('property_code')) has-error @endif'>
        <label for='property_code' class='col-sm-2 control-label'>property_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('property_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('property_code')) <p class="help-block">{{ $errors->first('property_code') }}</p> @endif
        </div>
    </div>    
    
	@include('form_properties.form_property')
{{ Form::close() }}

@endsection
