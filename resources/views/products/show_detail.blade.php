@extends('layouts.app')

@section('content')
@include('products.id')
<h1>View Product</h1>
<br>
{{ Form::model($product, ['id'=>'product_form', 'url'=>'products', 'class'=>'form-horizontal']) }} 
	<!-- Information -->
    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
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
						<label for='category_code' class='col-sm-4 control-label'>Category<span style='color:red;'> *</span></label>
						<div class='col-sm-8'>
            				{{ Form::label('category_code', $product->category->category_name, ['class'=>'form-control']) }}
							@if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						{{ Form::label('unit_code', 'Unit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('unit_code', $product->unit->unit_name, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_form')) has-error @endif'>
						<label for='order_form' class='col-sm-4 control-label'>Order Form</label>
						<div class='col-sm-8'>
            				{{ Form::label('order_form', $product->getOrderFormName(), ['class'=>'form-control']) }}
							@if ($errors->has('order_form')) <p class="help-block">{{ $errors->first('order_form') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('form_code')) has-error @endif'>
						{{ Form::label('form_code', 'Result Form',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::label('form_code', $product->getFormName(), ['class'=>'form-control']) }}
							@if ($errors->has('form_code')) <p class="help-block">{{ $errors->first('form_code') }}</p> @endif
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
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_stocked')) has-error @endif'>
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
	</div>

	<!-- Purchase -->
	@can('product_purchase_edit')
	<hr>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_purchase_price')) has-error @endif'>
						{{ Form::label('product_purchase_price', 'Purchase Price',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('product_purchase_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('product_purchase_price')) <p class="help-block">{{ $errors->first('product_purchase_price') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_reorder')) has-error @endif'>
						{{ Form::label('product_reorder', 'Reorder Limit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('product_reorder', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('product_reorder')) <p class="help-block">{{ $errors->first('product_reorder') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_conversion_unit')) has-error @endif'>
						{{ Form::label('product_conversion_unit', 'Conversion Unit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('product_conversion_unit', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('product_conversion_unit')) <p class="help-block">{{ $errors->first('product_conversion_unit') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_conversion_code')) has-error @endif'>
						{{ Form::label('product_conversion_code', 'Conversion Product',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('product_conversion_code', null, ['class'=>'form-control','placeholder'=>'Product code',]) }}
							@if ($errors->has('product_conversion_code')) <p class="help-block">{{ $errors->first('product_conversion_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	@endcan

	@can('product_sale_edit')
	<!-- Sale -->
	<hr>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_sale_price')) has-error @endif'>
						{{ Form::label('product_sale_price', 'Sale Price',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('product_sale_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('product_sale_price')) <p class="help-block">{{ $errors->first('product_sale_price') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_sale_margin')) has-error @endif'>
						{{ Form::label('product_sale_margin', 'Profit Margin',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::label('product_sale_margin', null, ['class'=>'form-control','placeholder'=>'', 'onkeyup'=>'UpdateSalePrice()']) }}
							@if ($errors->has('product_sale_margin')) <p class="help-block">{{ $errors->first('product_sale_margin') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('tax_code')) has-error @endif'>
						{{ Form::label('tax_code', 'Tax Code',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('tax_code', $tax_code,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
							@if ($errors->has('tax_code')) <p class="help-block">{{ $errors->first('tax_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_unit_charge')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_unit_charge', '1') }} <label>Prescription Charge</label><br>The charge amount depends on the total number of unit ordered. Only applicable to drugs.
							@if ($errors->has('product_unit_charge')) <p class="help-block">{{ $errors->first('product_unit_charge') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	@endcan

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group'>
						<div class="col-sm-offset-4 col-sm-8">
							<a class="btn btn-default" href="{{ url('/products') }}" role="button">Back</a>
							@can('product_information_edit')
							{{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
							@endcan
						</div>
					</div>
			</div>
			<div class="col-xs-6">
			</div>
	</div>

	<script>

		function UpdateSalePrice() {
			form = document.forms['product_form'];
			purchase_price = Number(form.product_purchase_price.value);
			profit_margin = Number(form.product_sale_margin.value)/100;

			product_sale_price = purchase_price*(1+profit_margin);
			form.product_sale_price.value= product_sale_price.toFixed(2);
		}	

         $(document).ready(function(){
             $("#product_form").validate({
                 rules: {
                     product_conversion_unit: {
                         number: true
                     },
                     product_purchase_price: {
                         number: true
                     },
                     product_reorder: {
                         number: true
                     },
                     product_sale_margin: {
                         number: true
                     },
                     product_sale_price: {
                         number: true
                     },
				 }
             });
        });

    </script>


{{ Form::close() }}
@endsection
