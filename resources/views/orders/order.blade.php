	
<h3>{{ $product->product_name }}</h3>
<h5>{{ $product->product_code }}</h5>
@if ($order->store)
<div class="alert @if ($available==0) alert-danger @else alert-success @endif">
	Available: {{ $available }} ({{ $order->store->store_name }})
</div>
@endif

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if (empty($order_single))
            <a class="btn btn-default" href="/orders" role="button">Back</a>
			@else
            <a class="btn btn-default" href="/orders/cancel_single/{{ $order->order_id }}" role="button">Cancel</a>
			@endif
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

   <div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
        <label for='order_quantity_request' class='col-sm-3 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('order_quantity_request', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_request')) <p class="help-block">{{ $errors->first('order_quantity_request') }}</p> @endif
        </div>
    </div>

	@if ($product->product_edit_price==1)
   <div class='form-group  @if ($errors->has('order_unit_price')) has-error @endif'>
        <label for='order_unit_price' class='col-sm-3 control-label'>Unit Price</label>
        <div class='col-sm-9'>
            {{ Form::text('order_unit_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_unit_price')) <p class="help-block">{{ $errors->first('order_unit_price') }}</p> @endif
        </div>
    </div>
   <div class='form-group  @if ($errors->has('order_discount')) has-error @endif'>
        <label for='order_discount' class='col-sm-3 control-label'>Discount</label>
        <div class='col-sm-9'>
            {{ Form::text('order_discount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_discount')) <p class="help-block">{{ $errors->first('order_discount') }}</p> @endif
        </div>
    </div>
	@endif

    <div class='form-group  @if ($errors->has('order_description')) has-error @endif'>
        {{ Form::label('Description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'3']) }}
            @if ($errors->has('order_description')) <p class="help-block">{{ $errors->first('order_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('Store', 'Store',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ Form::select('store_code', $store, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>
	<!--
    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        {{ Form::label('Send To', 'Send To',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('location_code', $location,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
        {{ Form::label('Discharge Order', 'Discharge Order',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('order_is_discharge', '1') }}
            @if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
        </div>
    </div>
	-->

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
						{{ Form::label('Discharge Order', 'Discharge Order',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('order_is_discharge', '1', $order->order_is_discharge) }}
							@if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_completed')) has-error @endif'>
						{{ Form::label('Order Completed', 'Order Completed',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('order_completed', '1', $order->order_completed) }}
							@if ($errors->has('order_completed')) <p class="help-block">{{ $errors->first('order_completed') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
            {{ Form::hidden('consultation_id', null) }}
            {{ Form::hidden('product_code', null) }}
            {{ Form::hidden('order_single', $order_single) }}
