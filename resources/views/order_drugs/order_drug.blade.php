
	<h3>{{ $product->product_name }}</h3>
	<div class="alert @if ($available==0) alert-danger @else alert-success @endif">
			Available: {{ $available }} ({{ $order_drug->order->store->store_name }})
	</div>
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
	-->


    <div class='form-group'>
        {{ Form::label('product_code', 'Code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
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
							{{ Form::select('dosage_code', $dosage,null, ['id'=>'dosage_code','class'=>'form-control input-sm','maxlength'=>'20']) }}
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
					<div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
						{{ Form::label('order_is_discharge', 'Discharge Order',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::checkbox('order_is_discharge', '1', $order->order_is_discharge) }}
							@if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

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
			@if ($product->product_unit_charge)
			dosage = document.getElementById('dosage').value;
			frequency = getFrequencyValue(document.getElementById('frequency').value) 
			period = getPeriodValue(document.getElementById('period').value) 
			duration = document.getElementById('duration').value;
			total = frequency*dosage;
			if (duration>0) total = total*duration*period;
			if (isNumber(total)==true) {
				document.getElementById('total').value=total;
			}
			@endif
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

</script>
