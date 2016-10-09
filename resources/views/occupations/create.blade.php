@extends('layouts.app')

@section('content')
<h1>
New Occupation
</h1>
@include('common.errors')
<br>
{{ Form::model($occupation, ['url'=>'occupations', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('occupation_code')) has-error @endif'>
        <label for='occupation_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('occupation_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('occupation_code')) <p class="help-block">{{ $errors->first('occupation_code') }}</p> @endif
        </div>
    </div>    
    
	@include('occupations.occupation')
{{ Form::close() }}

@endsection
