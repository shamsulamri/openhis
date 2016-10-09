@extends('layouts.app')

@section('content')
<h1>
New Urgency
</h1>
@include('common.errors')
<br>
{{ Form::model($urgency, ['url'=>'urgencies', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('urgency_code')) has-error @endif'>
        <label for='urgency_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('urgency_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('urgency_code')) <p class="help-block">{{ $errors->first('urgency_code') }}</p> @endif
        </div>
    </div>    
    
	@include('urgencies.urgency')
{{ Form::close() }}

@endsection
