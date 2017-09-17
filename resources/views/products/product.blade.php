
	<!-- Information -->
	@can('product_information_edit')
    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('product_name', null, ['id'=>'product_name','class'=>'form-control','placeholder'=>'','maxlength'=>'200','style'=>'text-transform: uppercase']) }}
            @if ($errors->has('product_name')) <p class="help-block">{{ $errors->first('product_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_name_other')) has-error @endif'>
        <label for='product_name_other' class='col-sm-2 control-label'>Other</label>
        <div class='col-sm-10'>
            {{ Form::text('product_name_other', null, ['id'=>'product_name_other','class'=>'form-control','placeholder'=>'Long, generic or other name for the product
','maxlength'=>'200']) }}
            @if ($errors->has('product_name_other')) <p class="help-block">{{ $errors->first('product_name_other') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
						<label for='category_code' class='col-sm-4 control-label'>Category<span style='color:red;'> *</span></label>
						<div class='col-sm-8'>
							{{ Form::select('category_code', $category,null, ['id'=>'category_code', 'class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_upc')) has-error @endif'>
						{{ Form::label('product_upc', 'UPC',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_upc', null, ['class'=>'form-control','placeholder'=>'Universal Product Code','maxlength'=>'20']) }}
							@if ($errors->has('product_upc')) <p class="help-block">{{ $errors->first('product_upc') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_form')) has-error @endif'>
						<label for='order_form' class='col-sm-4 control-label'>Order Form</label>
						<div class='col-sm-8'>
							{{ Form::select('order_form', $order_form,null, ['id'=>'order_form', 'class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('order_form')) <p class="help-block">{{ $errors->first('order_form') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('form_code')) has-error @endif'>
						{{ Form::label('form_code', 'Result Form',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('form_code', $form,null, ['class'=>'form-control','maxlength'=>'20']) }}
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
							{{ Form::select('location_code', $location,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_sku')) has-error @endif'>
						{{ Form::label('product_sku', 'SKU',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_sku', null, ['class'=>'form-control','placeholder'=>'Store Keeping Unit','maxlength'=>'100']) }}
							@if ($errors->has('product_sku')) <p class="help-block">{{ $errors->first('product_sku') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('status_code')) has-error @endif'>
						{{ Form::label('status_code', 'Status',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('status_code', $product_status,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_stocked')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_stocked', '1') }} <label>Stocked Product</label><br> Check for product you want to manage stock level for.
							@if ($errors->has('product_stocked')) <p class="help-block">{{ $errors->first('product_stocked') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_track_batch')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_track_batch', '1') }} <label>Batch Tracked</label><br>Batch number required during stock movement.
							@if ($errors->has('product_track_batch')) <p class="help-block">{{ $errors->first('product_track_batch') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_bom')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_bom', '1') }} <label>Bill of Materials</label><br>
							This product contains parts to make end product.
							@if ($errors->has('product_bom')) <p class="help-block">{{ $errors->first('product_bom') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_bom')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_dismantle_material', '1') }} <label>Assembly Part</label><br>Dismantled components return to stock when explode.
							@if ($errors->has('product_dismantle_material')) <p class="help-block">{{ $errors->first('product_dismantle_material') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	@else
		Put view here
	@endcan

	<!-- Purchase -->
	@can('product_purchase_edit')
	<hr>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_purchased')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_purchased', '1') }} <label>Purchase Product</label><br>This product require to be purchased.
							@if ($errors->has('product_purchased')) <p class="help-block">{{ $errors->first('product_purchased') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_purchase_price')) has-error @endif'>
						{{ Form::label('product_purchase_price', 'Purchase Price',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_purchase_price', null, ['class'=>'form-control','placeholder'=>'','onkeyup'=>'UpdateSalePrice()']) }}
							@if ($errors->has('product_purchase_price')) <p class="help-block">{{ $errors->first('product_purchase_price') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_conversion_unit')) has-error @endif'>
						{{ Form::label('product_conversion_unit', 'Conversion Unit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_conversion_unit', null, ['class'=>'form-control','placeholder'=>'','onkeyup'=>'UpdateSalePrice()']) }}
							@if ($errors->has('product_conversion_unit')) <p class="help-block">{{ $errors->first('product_conversion_unit') }}</p> @endif
						</div>
					</div>
					<!--
					<div class='form-group  @if ($errors->has('product_reorder')) has-error @endif'>
						{{ Form::label('product_reorder', 'Reorder Limit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_reorder', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('product_reorder')) <p class="help-block">{{ $errors->first('product_reorder') }}</p> @endif
						</div>
					</div>
					-->
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_cost')) has-error @endif'>
						{{ Form::label('product_cost', 'Unit Cost',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_cost', null, ['class'=>'form-control']) }}
							@if ($errors->has('product_cost')) <p class="help-block">{{ $errors->first('product_cost') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_purchase_unit')) has-error @endif'>
						{{ Form::label('product_purchase_unit', 'Purchase Unit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('product_purchase_unit', $unit,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('purchase_tax_code')) has-error @endif'>
						{{ Form::label('purchase_tax_code', 'Input Tax Code',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('purchase_tax_code', $tax_code,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
							@if ($errors->has('purchase_tax_code')) <p class="help-block">{{ $errors->first('purchase_tax_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_conversion_code')) has-error @endif'>
						{{ Form::label('product_conversion_code', 'Conversion Product',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_conversion_code', null, ['class'=>'form-control','placeholder'=>'Product code',]) }}
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
					<div class='form-group  @if ($errors->has('product_sold')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_sold', '1') }} <label>Sale Product</label><br>This product can be ordered for patients.
							@if ($errors->has('product_sold')) <p class="help-block">{{ $errors->first('product_sold') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('charge_code')) has-error @endif'>
						{{ Form::label('charge_code', 'Price Tier',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('charge_code', $charges ,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
							@if ($errors->has('charge_code')) <p class="help-block">{{ $errors->first('charge_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_sale_margin')) has-error @endif'>
						{{ Form::label('product_sale_margin', 'Profit Margin',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_sale_margin', null, ['class'=>'form-control','placeholder'=>'', 'onkeyup'=>'UpdateSalePrice()']) }}
							@if ($errors->has('product_sale_margin')) <p class="help-block">{{ $errors->first('product_sale_margin') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('tax_code')) has-error @endif'>
						{{ Form::label('tax_code', 'Output Tax Code',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('tax_code', $tax_code,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
							@if ($errors->has('tax_code')) <p class="help-block">{{ $errors->first('tax_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_sale_price')) has-error @endif'>
						{{ Form::label('product_sale_price', 'Sale Price',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('product_sale_price', null, ['class'=>'form-control','placeholder'=>'']) }}
							@if ($errors->has('product_sale_price')) <p class="help-block">{{ $errors->first('product_sale_price') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						{{ Form::label('unit_code', 'Sale Unit',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('unit_code', $unit,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_unit_charge')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_unit_charge', '1') }} <label>Prescription Charge</label><br>The charge amount depends on the total number of unit ordered. Only applicable to drugs.
							@if ($errors->has('product_unit_charge')) <p class="help-block">{{ $errors->first('product_unit_charge') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_drop_charge')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_drop_charge', '1') }} <label>Automatically Drop Charge</label><br>The item will be charged automatically upon ordering
							@if ($errors->has('product_drop_charge')) <p class="help-block">{{ $errors->first('product_drop_charge') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	@endcan

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group'>
						<div class="col-sm-offset-4 col-sm-8">
							<a class="btn btn-default" href="{{ url('/products') }}" role="button">Cancel</a>
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
			cost_unit = Number(form.product_purchase_price.value/form.product_conversion_unit.value);
			form.product_cost.value= cost_unit.toFixed(2);
			if (isNumeric(form.product_sale_margin.value)) {
					profit_margin = Number(form.product_sale_margin.value)/100;

					product_sale_price = cost_unit*(1+profit_margin);
					form.product_sale_price.value= product_sale_price.toFixed(2);
			}

		}	


		var isNumeric = function(obj){
				  return !Array.isArray( obj ) && (obj - parseFloat( obj ) + 1) >= 0;
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


