
    <div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
        {{ Form::label('product', 'Product',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
        {{ Form::label('product_code', 'Code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_strength')) has-error @endif'>
						{{ Form::label('drug_strength', 'Strength',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('drug_strength', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('drug_strength')) <p class="help-block">{{ $errors->first('drug_strength') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						<div class='col-md-12'>
							{{ Form::select('unit_code', $unit,null, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_dosage')) has-error @endif'>
						{{ Form::label('drug_dosage', 'Dosage',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('drug_dosage', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('drug_dosage')) <p class="help-block">{{ $errors->first('drug_dosage') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('dosage_code')) has-error @endif'>
						<div class='col-md-12'>
							{{ Form::select('dosage_code', $dosage,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('dosage_code')) <p class="help-block">{{ $errors->first('dosage_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('route_code')) has-error @endif'>
        {{ Form::label('route_code', 'Route',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('route_code', $route,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('route_code')) <p class="help-block">{{ $errors->first('route_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
        {{ Form::label('frequency_code', 'Frequency',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('frequency_code', $frequency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_period')) has-error @endif'>
						{{ Form::label('drug_period', 'Period',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('drug_period', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('drug_period')) <p class="help-block">{{ $errors->first('drug_period') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						<div class='col-md-12'>
							{{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
    <div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
        {{ Form::label('order_quantity_request', 'Total Unit',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_quantity_request', $order->order_quantity_request, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_request')) <p class="help-block">{{ $errors->first('order_quantity_request') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_description')) has-error @endif'>
        {{ Form::label('Description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('order_description', $order->order_description, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('order_description')) <p class="help-block">{{ $errors->first('order_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_prn')) has-error @endif'>
        {{ Form::label('drug_prn', 'PRN',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('drug_prn', '1') }}
            @if ($errors->has('drug_prn')) <p class="help-block">{{ $errors->first('drug_prn') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_after_meal')) has-error @endif'>
        {{ Form::label('drug_after_meal', 'After Meal',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('drug_after_meal', '1') }}
            @if ($errors->has('drug_after_meal')) <p class="help-block">{{ $errors->first('drug_after_meal') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
        {{ Form::label('order_is_discharge', 'Discharge Order',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_is_discharge', '1', $order->order_is_discharge) }}
            @if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/orders/{{ $consultation->consultation_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

    {{ Form::hidden('consultation_id', $consultation->consultation_id, ['class'=>'form-control','placeholder'=>'',]) }}
    {{ Form::hidden('product_code', $product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
