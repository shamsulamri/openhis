@extends('layouts.app')

@section('content')
<h1>
New Birth Complication
</h1>
@include('common.errors')
<br>
{{ Form::model($birth_complication, ['url'=>'birth_complications', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('complication_code')) has-error @endif'>
        <label for='complication_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('complication_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('complication_code')) <p class="help-block">{{ $errors->first('complication_code') }}</p> @endif
        </div>
    </div>    
    
	@include('birth_complications.birth_complication')
{{ Form::close() }}

@endsection
