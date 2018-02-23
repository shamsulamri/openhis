@extends('layouts.app')

@section('content')
<h1>
Edit Tier
</h1>
@include('common.errors')
<br>
{{ Form::model($product_charge, ['route'=>['product_charges.update',$product_charge->charge_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('charge_code', $product_charge->charge_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('product_charges.product_charge')
{{ Form::close() }}

@endsection
