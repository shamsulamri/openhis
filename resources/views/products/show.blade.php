@extends('layouts.app2')

@section('content')
@if ($reason=='bulk')
<a class="btn btn-default" href="{{ url('stock_inputs/input/'.$return_id) }}" role="button">Back</a>
@elseif ($reason=='purchase_order')
<a class="btn btn-default" href="{{ url('purchase_order_lines/index/'.$return_id) }}" role="button">Back</a>
@else
<a class="btn btn-default" href="{{ url('order_sets/index/'.$return_id) }}" role="button">Back</a>
@endif
<br>
<br>
{{ Form::model($product, ['id'=>'product_form', 'url'=>'products', 'class'=>'form-horizontal']) }} 
	<!-- Information -->
    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-2 control-label'>Name</label>
        <div class='col-sm-10'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control']) }}
            @if ($errors->has('product_name')) <p class="help-block">{{ $errors->first('product_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_name_other')) has-error @endif'>
        <label for='product_name_other' class='col-sm-2 control-label'>Other</label>
        <div class='col-sm-10'>
            {{ Form::label('', $product->product_name_other, ['id'=>'product_name_other','class'=>'form-control','placeholder'=>'Long, generic or other name for the product','maxlength'=>'200']) }}
            @if ($errors->has('product_name_other')) <p class="help-block">{{ $errors->first('product_name_other') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
						<label for='category_code' class='col-sm-4 control-label'>Category</label>
						<div class='col-sm-8'>
            				{{ Form::label('category_code', $product->category->category_name, ['class'=>'form-control']) }}
							@if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_upc')) has-error @endif'>
						{{ Form::label('product_upc', 'UPC',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('', $product->product_upc, ['class'=>'form-control']) }}
							@if ($errors->has('product_upc')) <p class="help-block">{{ $errors->first('product_upc') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('average_cost')) has-error @endif'>
						<label for='average_cost' class='col-sm-4 control-label'>Average Cost</label>
						<div class='col-sm-8'>
            				{{ Form::label('average_cost', $product->product_average_cost, ['class'=>'form-control']) }}
							@if ($errors->has('average_cost')) <p class="help-block">{{ $errors->first('average_cost') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('form_code')) has-error @endif'>
						{{ Form::label('form_code', 'Conversion Unit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('form_code', $product->product_conversion_unit, ['class'=>'form-control']) }}
							@if ($errors->has('form_code')) <p class="help-block">{{ $errors->first('form_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
						{{ Form::label('location_code', 'Receive by',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('location_code', $product->getLocationName(), ['class'=>'form-control']) }}
							@if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						{{ Form::label('unit_code', 'UOM',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('', $product->unitMeasure->unit_name, ['class'=>'form-control']) }}
							@if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('status_code')) has-error @endif'>
						{{ Form::label('status_code', 'Status',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('status_code', $product->status->status_name, ['class'=>'form-control']) }}
							@if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_sku')) has-error @endif'>
						{{ Form::label('product_sku', 'SKU',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('', $product->getProductSku(), ['class'=>'form-control','placeholder'=>'Store Keeping Unit','maxlength'=>'100']) }}
							@if ($errors->has('product_sku')) <p class="help-block">{{ $errors->first('product_sku') }}</p> @endif
						</div>
					</div>

			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_dismantle_material')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							@if ($product->product_stocked)
								<span class="fa fa-check-square-o" aria-hidden="true"></span>
							@else
								<span class="fa fa-square-o" aria-hidden="true"></span>
							@endif
							<label>Physical Stock</label><br> Check for product you want to manage stock level for.
							@if ($errors->has('product_stocked')) <p class="help-block">{{ $errors->first('product_stocked') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_bom')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							@if ($product->product_bom)
								<span class="fa fa-check-square-o" aria-hidden="true"></span>
							@else
								<span class="fa fa-square-o" aria-hidden="true"></span>
							@endif
							<label>Bill of Materials</label><br>
							This product contains parts to make end product.
							@if ($errors->has('product_bom')) <p class="help-block">{{ $errors->first('product_bom') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_bom')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							@if ($product->product_dismantle_material)
								<span class="fa fa-check-square-o" aria-hidden="true"></span>
							@else
								<span class="fa fa-square-o" aria-hidden="true"></span>
							@endif
							<label>Assembly Part</label><br>Dismantled components return to stock when explode.
							@if ($errors->has('product_dismantle_material')) <p class="help-block">{{ $errors->first('product_dismantle_material') }}</p> @endif
						</div>
					</div>
			</div>
	</div>



{{ Form::close() }}
@endsection
