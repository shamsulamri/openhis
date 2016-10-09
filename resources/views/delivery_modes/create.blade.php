@extends('layouts.app')

@section('content')
<h1>
New Delivery Mode
</h1>
@include('common.errors')
<br>
{{ Form::model($delivery_mode, ['url'=>'delivery_modes', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('delivery_code')) has-error @endif'>
        <label for='delivery_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('delivery_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('delivery_code')) <p class="help-block">{{ $errors->first('delivery_code') }}</p> @endif
        </div>
    </div>    
    
	@include('delivery_modes.delivery_mode')
{{ Form::close() }}

@endsection
