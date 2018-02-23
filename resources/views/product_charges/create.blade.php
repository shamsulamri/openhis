@extends('layouts.app')

@section('content')
<h1>
New Tier
</h1>
@include('common.errors')
<br>
{{ Form::model($product_charge, ['url'=>'product_charges', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('charge_code')) has-error @endif'>
        <label for='charge_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('charge_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('charge_code')) <p class="help-block">{{ $errors->first('charge_code') }}</p> @endif
        </div>
    </div>    
    
	@include('product_charges.product_charge')
{{ Form::close() }}

@endsection
