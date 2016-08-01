@extends('layouts.app')

@section('content')
<h1>
New Payment Method
</h1>
@include('common.errors')
<br>
{{ Form::model($payment_method, ['url'=>'payment_methods', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('payment_code')) has-error @endif'>
        <label for='payment_code' class='col-sm-3 control-label'>payment_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('payment_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('payment_code')) <p class="help-block">{{ $errors->first('payment_code') }}</p> @endif
        </div>
    </div>    
    
	@include('payment_methods.payment_method')
{{ Form::close() }}

@endsection
