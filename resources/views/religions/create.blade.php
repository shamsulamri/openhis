@extends('layouts.app')

@section('content')
<h1>
New Religion
</h1>
@include('common.errors')
<br>
{{ Form::model($religion, ['url'=>'religions', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('religion_code')) has-error @endif'>
        <label for='religion_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('religion_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('religion_code')) <p class="help-block">{{ $errors->first('religion_code') }}</p> @endif
        </div>
    </div>    
    
	@include('religions.religion')
{{ Form::close() }}

@endsection
