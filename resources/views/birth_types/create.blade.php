@extends('layouts.app')

@section('content')
<h1>
New Birth Type
</h1>
@include('common.errors')
<br>
{{ Form::model($birth_type, ['url'=>'birth_types', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('birth_code')) has-error @endif'>
        <label for='birth_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('birth_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('birth_code')) <p class="help-block">{{ $errors->first('birth_code') }}</p> @endif
        </div>
    </div>    
    
	@include('birth_types.birth_type')
{{ Form::close() }}

@endsection
