@extends('layouts.app')

@section('content')
<h1>
New Care Level
</h1>
@include('common.errors')
<br>
{{ Form::model($care_level, ['url'=>'care_levels', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('care_code')) has-error @endif'>
        <label for='care_code' class='col-sm-3 control-label'>care_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('care_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('care_code')) <p class="help-block">{{ $errors->first('care_code') }}</p> @endif
        </div>
    </div>    
    
	@include('care_levels.care_level')
{{ Form::close() }}

@endsection
