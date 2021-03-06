@extends('layouts.app')

@section('content')
<h1>
New State
</h1>

<br>
{{ Form::model($state, ['url'=>'states', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('state_code')) has-error @endif'>
        <label for='state_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('state_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('state_code')) <p class="help-block">{{ $errors->first('state_code') }}</p> @endif
        </div>
    </div>    
    
	@include('states.state')
{{ Form::close() }}

@endsection
