@extends('layouts.app')

@section('content')
<script>
	function UpdateSalePrice() {
			alert("X");
	}	
</script>
<h1>New Product</h1>
@include('common.errors')
<br>
{{ Form::model($product, ['url'=>'products', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('product_code', null, ['class'=>'form-control','placeholder'=>'Product reference, store keeping unit or other unique value', 'maxlength'=>'20.0']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>    
    
	@include('products.product')
{{ Form::close() }}

@endsection
