
	<!-- Information -->
	@can('product_information_edit')
    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('product_name', null, ['id'=>'product_name','class'=>'form-control','placeholder'=>'','maxlength'=>'250','style'=>'text-transform: uppercase']) }}
            @if ($errors->has('product_name')) <p class="help-block">{{ $errors->first('product_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_name_other')) has-error @endif'>
        <label for='product_name_other' class='col-sm-2 control-label'>Other</label>
        <div class='col-sm-10'>
            {{ Form::text('product_name_other', null, ['id'=>'product_name_other','class'=>'form-control','placeholder'=>'Long, generic or other name for the product
','maxlength'=>'250']) }}
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
					<div class='form-group  @if ($errors->has('product_custom_id')) has-error @endif'>
						{{ Form::label('product_custom_id', 'Custom Id',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
            				{{ Form::text('product_custom_id', null, ['id'=>'product_custom_id','class'=>'form-control','placeholder'=>'Rerefence to external code eg. MMA']) }}
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_input_tax')) has-error @endif'>
						{{ Form::label('product_input_tax', 'Input Tax Code',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('product_input_tax', $tax_code,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
							@if ($errors->has('product_input_tax')) <p class="help-block">{{ $errors->first('product_input_tax') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_output_tax')) has-error @endif'>
						{{ Form::label('product_output_tax', 'Output Tax Code',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('product_output_tax', $tax_code,null, ['class'=>'form-control','maxlength'=>'20', ]) }}
							@if ($errors->has('product_output_tax')) <p class="help-block">{{ $errors->first('product_output_tax') }}</p> @endif
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
					<div class='form-group  @if ($errors->has('status_code')) has-error @endif'>
						{{ Form::label('status_code', 'Status',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('status_code', $product_status,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('charge_code')) has-error @endif'>
						{{ Form::label('charge_code', 'Price Tier',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('charge_code', $charges ,null, ['id'=>'charge_code', 'class'=>'form-control','maxlength'=>'20', ]) }}
							@if ($errors->has('charge_code')) <p class="help-block">{{ $errors->first('charge_code') }}</p> @endif
							<br>
							<a href='javascript:showTier()'>Show price tiers</a>
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_stocked')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_stocked', '1') }} <label>Stock Control</label><br>Product is a stocked item.
							@if ($errors->has('product_stocked')) <p class="help-block">{{ $errors->first('product_stocked') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	@else
		Put view here
	@endcan

	@can('product_sale_edit')
	<!-- Sale -->
	<hr>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_local_store')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_local_store', '1') }} <label>Local Store</label><br>Stock taken from local store when consumed.
							@if ($errors->has('product_local_store')) <p class="help-block">{{ $errors->first('product_local_store') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_edit_price')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_edit_price', '1') }} <label>Allow price edit</label><br>Allow orderer to edit the sale price during ordering
							@if ($errors->has('product_edit_price')) <p class="help-block">{{ $errors->first('product_edit_price') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_drop_charge')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_drop_charge', '1') }} <label>Drop Charge</label><br>Item will be automatically charge upon closing the consultation.
							@if ($errors->has('product_drop_charge')) <p class="help-block">{{ $errors->first('product_drop_charge') }}</p> @endif
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
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_non_claimable')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_non_claimable', '1') }} <label>Non-claimable</label><br>This is a non-claimable item.
							@if ($errors->has('product_non_claimable')) <p class="help-block">{{ $errors->first('product_non_claimable') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('product_duration_use')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
							{{ Form::checkbox('product_duration_use', '1') }} <label>Duration Use</label><br>Hourly charged
							@if ($errors->has('product_duration_use')) <p class="help-block">{{ $errors->first('product_duration_use') }}</p> @endif
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

		function showTier() {
				var tier = document.getElementById('charge_code');
				window.location.href = "/product_price_tiers/"+tier.value;
		}
    </script>


