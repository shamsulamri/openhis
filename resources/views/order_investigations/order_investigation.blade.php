	<h3>{{ $product->product_name }}</h3>
	<h5>{{ $product->product_code }}</h5>
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

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
						<label for='investigation_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
						<div class='col-sm-9'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="investigation_date" id="investigation_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($order_investigation->investigation_date) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('investigation_date')) <p class="help-block">{{ $errors->first('investigation_date') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('urgency_code')) has-error @endif'>
						{{ Form::label('urgency_code', 'Urgency',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::select('urgency_code', $urgency,null, ['class'=>'form-control input-sm','maxlength'=>'20']) }}
							@if ($errors->has('urgency_code')) <p class="help-block">{{ $errors->first('urgency_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('investigation_duration')) has-error @endif'>
						{{ Form::label('investigation_duration', 'Duration',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('investigation_duration', null, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
							@if ($errors->has('investigation_duration')) <p class="help-block">{{ $errors->first('investigation_duration') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						{{ Form::label('period', 'Period',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('period_code', $period,null, ['class'=>'form-control input-sm','maxlength'=>'10']) }}
							@if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
						{{ Form::label('frequency_code', 'Frequency',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::select('frequency_code', $frequency,null, ['class'=>'form-control input-sm','maxlength'=>'20']) }}
							@if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
						<label for='order_quantity_request' class='col-sm-3 control-label'>Quantity<span style='color:red;'> *</span></label>
						<div class='col-sm-9'>
							{{ Form::text('order_quantity_request', $order->order_quantity_request, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
							@if ($errors->has('order_quantity_request')) <p class="help-block">{{ $errors->first('order_quantity_request') }}</p> @endif
						</div>
					</div>
			</div>
	</div>



    <div class='form-group  @if ($errors->has('order_description')) has-error @endif'>
        {{ Form::label('Description', 'Description / Instruction',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_description', $order->order_description, ['class'=>'form-control input-sm','placeholder'=>'','rows'=>'3']) }}
            @if ($errors->has('order_description')) <p class="help-block">{{ $errors->first('order_description') }}</p> @endif
        </div>
    </div>

	<hr>

	@if (Auth::user()->consultant)
    <div class='form-group  @if ($errors->has('order_report')) has-error @endif'>
        {{ Form::label('Report', 'Report',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_report', $order->order_report, ['class'=>'form-control input-sm','placeholder'=>'','rows'=>'3']) }}
            @if ($errors->has('order_report')) <p class="help-block">{{ $errors->first('order_report') }}</p> @endif
        </div>
    </div>
	@endif

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

    {{ Form::hidden('consultation_id', $consultation->consultation_id, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
    {{ Form::hidden('product_code', $product->product_code, ['class'=>'form-control input-sm','placeholder'=>'',]) }}
	<script>
		$(function(){
				/*
				$('#investigation_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MM YYYY",
						value: '{{ $order_investigation->investigation_date }}',
						maxYear: 2016,
						minYear: 1900,
						customClass: 'select'
				});    
				 */
				$('#investigation_date').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
				});
		});
	</script>
