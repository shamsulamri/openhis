@extends('layouts.app2')

@section('content')
<h3>View Product</h3>
<br>
{{ Form::model($product, [ 'class'=>'form-horizontal', 'name'=>'product_form']) }} 

    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>Name</label>
        <div class='col-sm-9'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>Code</label>
        <div class='col-sm-9'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
        <label for='category_code' class='col-sm-3 control-label'>Category</label>
        <div class='col-sm-9'>
            {{ Form::label('category', $product->category->category_name, ['class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
        <label for='category_code' class='col-sm-3 control-label'>Sale Price</label>
        <div class='col-sm-9'>
            {{ Form::label('product', $product->product_sale_price, ['class'=>'form-control','maxlength'=>'20']) }}
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if ($reason=='purchase_order')
            <a class="btn btn-default" href="/purchase_order_lines/index/{{ $return_id }}" role="button">Back</a>
			@endif
			@if ($reason=='bom')
            <a class="btn btn-default" href="/bill_materials/index/{{ $return_id }}" role="button">Back</a>
			@endif
			@if ($reason=='asset')
            <a class="btn btn-default" href="/order_sets/index/{{ $return_id }}" role="button">Back</a>
			@endif
        </div>
    </div>

{{ Form::close() }}
@endsection
