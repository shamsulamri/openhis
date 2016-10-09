@extends('layouts.app')

@section('content')
<h1>
New Form
</h1>
@include('common.errors')
<br>
{{ Form::model($form, ['url'=>'forms', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('form_code')) has-error @endif'>
        <label for='form_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('form_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('form_code')) <p class="help-block">{{ $errors->first('form_code') }}</p> @endif
        </div>
    </div>    
    
	@include('forms.form')
{{ Form::close() }}

@endsection
