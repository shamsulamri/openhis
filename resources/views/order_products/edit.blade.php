@extends('layouts.app')

@section('content')
<h1>
Edit Order Product
</h1>
@include('common.errors')
<br>
{{ Form::model($order_product, ['route'=>['order_products.update',$order_product->product_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('product_code', $order_product->product_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('order_products.order_product')
{{ Form::close() }}

@endsection
