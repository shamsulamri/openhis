@extends('layouts.app')

@section('content')
<h1>
New Diet
</h1>
@include('common.errors')
<br>
{{ Form::model($diet, ['url'=>'diets', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('diet_code')) has-error @endif'>
        <label for='diet_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('diet_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('diet_code')) <p class="help-block">{{ $errors->first('diet_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diets.diet')
{{ Form::close() }}

@endsection
