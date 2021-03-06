
	<h3>{{ $product->product_name }}</h3>
	<h5>{{ $product->product_code }}</h5>
<?php 
	$stock_count = 0;
	$store = null;
?>
@if (!empty(Session::get('local_store')))
		<?php
			$stock_count = Session::get('stock_count');
			$store = Session::get('local_store');
		?>
@else
		<?php
			$stock_count = $available;
			$store = $order_drug->order->store;
		?>
@endif

@if (!empty($store))
	<div class="alert @if ($stock_count<=0) alert-danger @else alert-success @endif">
			Available: {{ $stock_count }} {{ '('.$store->store_name.')' }} 
	</div>
@endif
	<!--
	@if ($errors)
		@foreach($errors->all() as $message)
				{{ $message }}
		@endforeach
	@endif
	-->

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if (empty($order->product_code))
            <a class="btn btn-default" href="/order_products" role="button">Back</a>
			@else
            <a class="btn btn-default" href="/orders" role="button">Back</a>
			@endif
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<!--
    <div class='form-group'>
        {{ Form::label('prescription', 'Select Prescription',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
			<select class="form-control" onchange="changePrescription()" id="prescription" name="prescription">
				@foreach($prescriptions as $ps)
					<option value='{{ $ps->drug_dosage }};{{ $ps->dosage_code }};{{ $ps->frequency_code}};{{ $ps->route_code}}'>{{ $ps->route_name }} - {{ $ps->frequency_name }}</option>
				@endforeach
			</select>
        </div>
    </div>


    <div class='form-group'>
        {{ Form::label('product_code', 'Code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	-->

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_strength')) has-error @endif'>
						{{ Form::label('drug_strength', 'Strength',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('drug_strength', null, ['id'=>'drug_strength','class'=>'form-control input-sm','placeholder'=>'']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						{{ Form::label('unit', '&nbsp;',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('unit_code', $unit,null, ['id'=>'unit_code','class'=>'form-control input-sm','maxlength'=>'20']) }}
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
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('dosage_code')) has-error @endif'>
						{{ Form::label('unit', '&nbsp;',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('dosage_code', $dosage,null, ['id'=>'dosage_code','class'=>'form-control input-sm','maxlength'=>'20','onchange'=>'countTotalUnit()']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('route_code')) has-error @endif'>
						{{ Form::label('route_code', 'Route',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('route_code', $route,null, ['id'=>'route','class'=>'form-control input-sm','maxlength'=>'20']) }}
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
			</div>
	</div>

    <div class='form-group  @if ($errors->has('order_description')) has-error @endif'>
        {{ Form::label('Description', 'Instruction',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_description', $order->order_description, ['class'=>'form-control','placeholder'=>'','rows'=>'3']) }}
            @if ($errors->has('order_description')) <p class="help-block">{{ $errors->first('order_description') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
						{{ Form::label('order_is_discharge', 'Discharge Order',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('order_is_discharge', '1', $order->order_is_discharge) }}
							@if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_include_stat')) has-error @endif'>
						{{ Form::label('order_include_stat', 'Include STAT',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('order_include_stat', '1', $order->order_include_stat,['onchange'=>'countTotalUnit()']) }}
							@if ($errors->has('order_include_stat')) <p class="help-block">{{ $errors->first('order_include_stat') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<!--
	<div class="row">
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
	-->
@if ($product->drug)
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
					<div class='form-group  @if ($errors->has('label')) has-error @endif'>
						{{ Form::label('indication', 'Indication',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							@foreach ($indications as $indication)
								{{ $indication->indication->indication_description }}
								@if ($indication != $indications->last())
								, 
								@endif
							@endforeach
						</div>
					</div>
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
					<div class='form-group  @if ($errors->has('label')) has-error @endif'>
						{{ Form::label('instruction', 'Instruction',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
						@if ($product->drug)
							@if ($product->drug->instruction)
							- {{ $product->drug->instruction->instruction_english }}
							<br>
							- {{ $product->drug->instruction->instruction_bahasa }}
							@else
							NA
							@endif
						@endif
						</div>
					</div>
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
					<div class='form-group  @if ($errors->has('label')) has-error @endif'>
						{{ Form::label('special', 'Special Instruction',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
						@if ($product->drug)
							@if ($product->drug->special)
							- {{ $product->drug->special->special_instruction_english }}
							<br>
							- {{ $product->drug->special->special_instruction_bahasa }}
							@else
							NA
							@endif
						@endif
						</div>
					</div>
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
					<div class='form-group  @if ($errors->has('label')) has-error @endif'>
						{{ Form::label('caution', 'Caution',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
						@if ($product->drug)
							@if ($product->drug->caution)
							- {{ $product->drug->caution->caution_english }}
							<br>
							- {{ $product->drug->caution->caution_bahasa }}
							@else
							NA
							@endif
						@endif
						</div>
					</div>
        </div>
    </div>
@endif

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

			var dosage_count_units = [{!! $dosage_count_units !!}];

			if (document.getElementById('frequency').value == 'STAT') {
				document.getElementById('order_include_stat').checked = false;
				document.getElementById('order_include_stat').disabled = true;
			} else {
				document.getElementById('order_include_stat').disabled = false;
			}

			unit_code = document.getElementById('unit_code').value;
			dosage_code = document.getElementById('dosage_code').value;
			strength = document.getElementById('drug_strength').value;
			dosage = document.getElementById('dosage').value;

			if (dosage_count_units.includes(dosage_code)) {

					frequency = getFrequencyValue(document.getElementById('frequency').value) 
					period = getPeriodValue(document.getElementById('period').value) 
					duration = document.getElementById('duration').value;
					total = Math.round(frequency*dosage);

					console.log("Dosage:"+dosage);
					console.log("Frequency:"+frequency);
					console.log("Duration:"+duration);

					if (duration>0) total = total*duration*period;
					if (isNumber(total)==true) {
						document.getElementById('total').value=total;
					}
			}
	}

	function isNumber(n) {
			return !isNaN(parseFloat(n)) && isFinite(n);
	}

	function changePrescription() {
		ps = document.getElementById('prescription');

		values = ps.value.split(";");

		dosage = document.getElementById('dosage');
		dosage_code = document.getElementById('dosage_code');
		frequency = document.getElementById('frequency');
		route = document.getElementById('route');

		dosage.value = values[0];
		dosage_code.value = values[1];
		frequency.value = values[2];
		route.value = values[3];
	}

	countTotalUnit();
</script>
