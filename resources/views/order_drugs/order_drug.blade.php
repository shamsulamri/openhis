
	<h3>{{ $product->product_name }}</h3>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if (empty($order->product_code))
            <a class="btn btn-default" href="/order_products" role="button">Cancel</a>
			@else
            <a class="btn btn-default" href="/orders" role="button">Cancel</a>
			@endif
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_strength')) has-error @endif'>
						{{ Form::label('drug_strength', 'Strength',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('drug_strength', null, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
							@if ($errors->has('drug_strength')) <p class="help-block">{{ $errors->first('drug_strength') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						{{ Form::label('unit', '&nbsp;',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('unit_code', $unit,null, ['class'=>'form-control input-sm','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_dosage')) has-error @endif'>
						{{ Form::label('drug_dosage', 'Dosage',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('drug_dosage', null, ['id'=>'dosage','class'=>'form-control input-sm','placeholder'=>'','onchange'=>'countTotalUnit()',]) }}
							@if ($errors->has('drug_dosage')) <p class="help-block">{{ $errors->first('drug_dosage') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('dosage_code')) has-error @endif'>
						{{ Form::label('unit', '&nbsp;',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('dosage_code', $dosage,null, ['class'=>'form-control input-sm','maxlength'=>'20']) }}
							@if ($errors->has('dosage_code')) <p class="help-block">{{ $errors->first('dosage_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('route_code')) has-error @endif'>
						{{ Form::label('route_code', 'Route',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('route_code', $route,null, ['class'=>'form-control input-sm','maxlength'=>'20']) }}
							@if ($errors->has('route_code')) <p class="help-block">{{ $errors->first('route_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
						{{ Form::label('frequency_code', 'Frequency',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('frequency_code', $frequency,null, ['id'=>'frequency','class'=>'form-control input-sm','maxlength'=>'20','onchange'=>'countTotalUnit()']) }}
							@if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_duration')) has-error @endif'>
						{{ Form::label('drug_duration', 'Duration',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('drug_duration', null, ['id'=>'duration','class'=>'form-control input-sm','placeholder'=>'','onchange'=>'countTotalUnit()',]) }}
							@if ($errors->has('drug_duration')) <p class="help-block">{{ $errors->first('drug_duration') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						{{ Form::label('unit', 'Period',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('period_code', $period,null, ['id'=>'period', 'class'=>'form-control input-sm','maxlength'=>'10','onchange'=>'countTotalUnit()']) }}
							@if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
						{{ Form::label('order_quantity_request', 'Total Unit',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('order_quantity_request', $order->order_quantity_request, ['id'=>'total','class'=>'form-control input-sm','placeholder'=>'',]) }}
							@if ($errors->has('order_quantity_request')) <p class="help-block">{{ $errors->first('order_quantity_request') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_prn')) has-error @endif'>
						{{ Form::label('drug_prn', 'PRN',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('drug_prn', '1') }}
							@if ($errors->has('drug_prn')) <p class="help-block">{{ $errors->first('drug_prn') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_meal')) has-error @endif'>
						{{ Form::label('drug_meal', 'After Meal',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('drug_meal', '1') }}
							@if ($errors->has('drug_meal')) <p class="help-block">{{ $errors->first('drug_meal') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
						{{ Form::label('order_is_discharge', 'Discharge Order',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('order_is_discharge', '1', $order->order_is_discharge) }}
							@if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('order_description')) has-error @endif'>
        {{ Form::label('Description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_description', $order->order_description, ['class'=>'form-control input-sm','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('order_description')) <p class="help-block">{{ $errors->first('order_description') }}</p> @endif
        </div>
    </div>




    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if (empty($order->product_code))
            <a class="btn btn-default" href="/order_products" role="button">Cancel</a>
			@else
            <a class="btn btn-default" href="/orders" role="button">Cancel</a>
			@endif
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

    {{ Form::hidden('consultation_id', $consultation->consultation_id, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
    {{ Form::hidden('product_code', $product->product_code, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
<script>
	function getPeriodValue(periodCode) {
		if (periodCode=='day') return 1;
		if (periodCode=='week') return 7;
		if (periodCode=='month') return 30;
	}

	function getFrequencyValue(frequencyCode) {
			@foreach($frequencyValues as $f)
					if (frequencyCode=='{{ $f->frequency_code }}') return {{ $f->frequency_value }};
			@endforeach
	}

	function countTotalUnit() {
			dosage = document.getElementById('dosage').value;
			frequency = getFrequencyValue(document.getElementById('frequency').value) 
			period = getPeriodValue(document.getElementById('period').value) 
			duration = document.getElementById('duration').value;
			total = frequency*duration*period*dosage;
			document.getElementById('total').value=total;
	}
</script>
