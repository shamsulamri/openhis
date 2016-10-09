@extends('layouts.app')

@section('content')
<h1>
Edit Payment Method
</h1>
@include('common.errors')
<br>
{{ Form::model($payment_method, ['route'=>['payment_methods.update',$payment_method->payment_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('payment_code', $payment_method->payment_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('payment_methods.payment_method')
{{ Form::close() }}

@endsection
