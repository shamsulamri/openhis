@extends('layouts.app')

@section('content')
<h1>
New Order Set
</h1>
@include('common.errors')
<br>
{{ Form::model($set, ['url'=>'sets', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('set_code')) has-error @endif'>
        <label for='set_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('set_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('set_code')) <p class="help-block">{{ $errors->first('set_code') }}</p> @endif
        </div>
    </div>    
    
	@include('sets.set')
{{ Form::close() }}

@endsection
