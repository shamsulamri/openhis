@extends('layouts.app')

@section('content')
<h1>
New Diet Enteral
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_enteral, ['url'=>'diet_enterals', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('enteral_code')) has-error @endif'>
        <label for='enteral_code' class='col-sm-2 control-label'>enteral_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('enteral_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10']) }}
            @if ($errors->has('enteral_code')) <p class="help-block">{{ $errors->first('enteral_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_enterals.diet_enteral')
{{ Form::close() }}

@endsection