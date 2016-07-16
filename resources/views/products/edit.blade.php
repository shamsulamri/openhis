@extends('layouts.app')

@section('content')
<script>
	function UpdateSalePrice() {
			form = document.forms['product_form'];
			purchase_price = Number(form.product_purchase_price.value);
			profit_margin = Number(form.product_sale_margin.value)/100;

			product_sale_price = purchase_price*(1+profit_margin);
			form.product_sale_price.value= product_sale_price.toFixed(2);
	}	
</script>
@include('products.id')
<h1>Edit Product</h1>
<a class="btn btn-default" href="/products/{{ $product->product_code }}/option" role="button">Back</a>
<br>
<br>
@include('common.errors')
{{ Form::model($product, ['route'=>['products.update',$product->product_code],'method'=>'PUT', 'class'=>'form-horizontal', 'name'=>'product_form']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('products.product')
{{ Form::close() }}

@endsection
