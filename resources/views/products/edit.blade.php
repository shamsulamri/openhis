@extends('layouts.app')

@section('content')
<h1>
Edit Product
</h1>
@include('common.errors')
<br>
{{ Form::model($product, ['route'=>['products.update',$product->product_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('products.product')
{{ Form::close() }}

@endsection
