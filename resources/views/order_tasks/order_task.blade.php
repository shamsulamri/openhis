    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>Item</label>
        <div class='col-sm-9'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='user' class='col-sm-3 control-label'>Ordered By</label>
        <div class='col-sm-9'>
            {{ Form::label('user', $order_task->user->name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

	@if ($order_task->orderDrug)
   <div class='form-group  @if ($errors->has('order_quantity_supply')) has-error @endif'>
		<label for='order_quantity_supply' class='col-sm-3 control-label'>Quantity<span style='color:red;'> *</span></label>
		<div class='col-sm-9'>
			{{ Form::text('order_quantity_supply', null, ['class'=>'form-control','placeholder'=>'',]) }}
			@if ($errors->has('order_quantity_supply')) <p class="help-block">{{ $errors->first('order_quantity_supply') }}</p> @endif
		</div>
	</div>
	@endif

	@if ($report)
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='user' class='col-sm-3 control-label'>Consultation Date</label>
        <div class='col-sm-9'>
            {{ Form::label('execute', DojoUtility::dateTimeReadFormat($order_task->consultation->created_at), ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('order_report')) has-error @endif'>
        {{ Form::label('order_report', 'Report',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_report', null, ['id'=>'order_report','onkeyup'=>'taskCompleted()','class'=>'form-control','placeholder'=>'','rows'=>'25']) }}
        </div>
    </div>
	@endif

	@if (Auth::user()->author_id == 5)
    <div class='form-group  @if ($errors->has('order_custom_id')) has-error @endif'>
        {{ Form::label('order_custom_id', 'Lab Number',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('order_custom_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_custom_id')) <p class="help-block">{{ $errors->first('order_custom_id') }}</p> @endif
        </div>
    </div>
	@endif

	@if ($product->product_stocked)
    <div class='form-group  @if ($errors->has('stock_code')) has-error @endif'>
        {{ Form::label('stock_code', 'Store',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
    		{{ Form::select('store_code', $store, null, ['class'=>'form-control']) }}
        </div>
    </div>
	@endif

	@if (Auth::user()->authorization->module_support==1)
		@if (!empty($order_task->drugLabel->order_id))
			<!--
			<div class='form-group  @if ($errors->has('order_discount')) has-error @endif'>
				{{ Form::label('drug_dosage', 'Dosage',['class'=>'col-sm-3 control-label']) }}
				<div class='col-sm-1'>
					{{ Form::text('drug_dosage', $order_task->drugLabel->drug_dosage, ['class'=>'form-control','placeholder'=>'',]) }}
				</div>
				<div class='col-sm-2'>
					{{ Form::select('dosage_code', $dosage,$order_task->drugLabel->dosage_code, ['id'=>'dosage_code','class'=>'form-control input-sm']) }}
				</div>
			</div>
			-->
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='user' class='col-sm-3 control-label'>Original Prescription</label>
        <div class='col-sm-9'>
            {{ Form::label('user', $order_helper->getPrescription($order_task->order_id), ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_strength')) has-error @endif'>
						{{ Form::label('drug_strength', 'Strength',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('drug_strength', $order_task->drugLabel->drug_strength, ['id'=>'drug_strength','class'=>'form-control input-sm','placeholder'=>'']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						<div class='col-md-6'>
							{{ Form::select('unit_code', $unit, $order_task->drugLabel->unit_code, ['id'=>'unit_code','class'=>'form-control input-sm','maxlength'=>'20']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_dosage')) has-error @endif'>
						{{ Form::label('drug_dosage', 'Dosage',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('drug_dosage', $order_task->drugLabel->drug_dosage, ['id'=>'dosage','class'=>'form-control input-sm','placeholder'=>'','onchange'=>'countTotalUnit()',]) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('dosage_code')) has-error @endif'>
						<div class='col-md-6'>
							{{ Form::select('dosage_code', $dosage,$order_task->drugLabel->dosage_code, ['id'=>'dosage_code','class'=>'form-control input-sm','maxlength'=>'20','onchange'=>'countTotalUnit()']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('route_code')) has-error @endif'>
						{{ Form::label('route_code', 'Route',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::select('route_code', $route,$order_task->drugLabel->route_code, ['id'=>'route','class'=>'form-control input-sm','maxlength'=>'20']) }}
							@if ($errors->has('route_code')) <p class="help-block">{{ $errors->first('route_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
						{{ Form::label('frequency_code', 'Frequency',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::select('frequency_code', $frequency,$order_task->drugLabel->frequency_code, ['id'=>'frequency','class'=>'form-control input-sm','maxlength'=>'20']) }}
							@if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_duration')) has-error @endif'>
						{{ Form::label('drug_duration', 'Duration',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('drug_duration', $order_task->drugLabel->drug_duration, ['id'=>'duration','class'=>'form-control input-sm','placeholder'=>'','onchange'=>'countTotalUnit()',]) }}
							@if ($errors->has('drug_duration')) <p class="help-block">{{ $errors->first('drug_duration') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						<div class='col-md-6'>
							{{ Form::select('period_code', $period,$order_task->drugLabel->period_code, ['id'=>'period', 'class'=>'form-control input-sm','maxlength'=>'10','onchange'=>'countTotalUnit()']) }}
							@if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

		@endif
	@endif

<!--
@if ($order_task->product->uomDefaultPrice($encounter))
    <div class='form-group  @if ($errors->has('order_quantity_supply')) has-error @endif'>
        {{ Form::label('order_quantity_supply', 'Quantity Supply',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-1'>
            {{ Form::text('order_quantity_supply', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_supply')) <p class="help-block">{{ $errors->first('order_quantity_supply') }}</p> @endif
        </div>
        <div class='col-sm-1'>
        	{{ Form::label('order_quantity_supply', $order_task->product->uomDefaultPrice($encounter)->unit_code,['class'=>'control-label']) }}
        </div>
    </div>
@endif
-->


	<!--
    <div class='form-group  @if ($errors->has('order_discount')) has-error @endif'>
        {{ Form::label('order_discount', 'Discount',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('order_discount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_discount')) <p class="help-block">{{ $errors->first('order_discount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_completed')) has-error @endif'>
        {{ Form::label('order_completed', 'Completed',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('order_completed', '1',null,['id'=>'order_completed']) }}
            @if ($errors->has('order_completed')) <p class="help-block">{{ $errors->first('order_completed') }}</p> @endif
        </div>
    </div>
	-->

	
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			@if ($report)
					<a class="btn btn-default" href="/order_queues/report" role="button">Back</a>
					@else
					<a class="btn btn-default" href="/order_tasks/task/{{ $encounter_id }}/{{ $order_task->product->location_code }}" role="button">Back</a>
			@endif
			@if ($report)
					@if (!empty($order_task->order_report))
					<a target="_blank" class='btn btn-success pull-right' href="{{ Config::get('host.report_server')  }}/ReportServlet?report=order_report&id={{ $order_task->order_id }}">
						Print
					</a>
					@else
					<a class='btn btn-success pull-right disabled' href="#">
						Print
					</a>
					@endif
			@endif
        </div>
    </div>
			@if ($report)
            {{ Form::hidden('report', 1) }}
			@endif
            {{ Form::hidden('consultation_id', null) }}
            {{ Form::hidden('mar', $mar) }}


<!--
<script type="text/javascript">
	function taskCompleted() {
		var report = document.getElementById('order_report').value;

		if (report.trim()) {
				document.getElementById('order_completed').checked = true;
		} else {
				document.getElementById('order_completed').checked = false;
		}

	}

</script>
-->


