medication_fail('common.errors')
			<div class='form-group  @if ($errors->has('medication_datetime')) has-error @endif'>
    		    <label for='order' class='col-sm-2 control-label'>Drug</label>
				<div class='col-sm-10'>
					{{ Form::label('drug', $order->product->product_name, ['class'=>'form-control']) }}
				</div>
			</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('medication_date')) has-error @endif'>
						{{ Form::label('Date', 'Date',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="medication_date" id="medication_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($medication_record->medication_datetime) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('medication_time')) has-error @endif'>
						{{ Form::label('Time', 'Time',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
								<div id="medication_time" name="medication_time" class="input-group clockpicker" data-autoclose="true">
										{{ Form::text('medication_time', DojoUtility::timeReadFormat($medication_record->medication_datetime), ['class'=>'form-control','data-mask'=>'99:99']) }}
										<span class="input-group-addon">
											<span class="fa fa-clock-o"></span>
										</span>
								</div>

						</div>
					</div>
			</div>
	</div>
	<div class='form-group  @if ($errors->has('medication_description')) has-error @endif'>
		<label for='order' class='col-sm-2 control-label'>Description</label>
		<div class='col-sm-10'>
            {{ Form::textarea('medication_description', null, ['class'=>'form-control','rows'=>'4']) }}
		</div>
	</div>
	<div class='form-group  @if ($errors->has('medication_fail')) has-error @endif'>
		<label for='order' class='col-sm-2 control-label'>Miss</label>
		<div class='col-sm-10'>
			{{ Form::checkbox('medication_fail', '1',null,['id'=>'medication_fail']) }}
		</div>
	</div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/medication_record/mar/{{ $order->encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('index', $index) }}
	{{ Form::hidden('slot', $slot) }}
	{{ Form::hidden('order_id', $order->order_id) }}
<script>
		$('.clockpicker').clockpicker();

		$('#medication_date').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
		});
</script>

<!--
    <div class='form-group  @if ($errors->has('order_id')) has-error @endif'>
        <label for='order_id' class='col-sm-2 control-label'>order_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_id')) <p class="help-block">{{ $errors->first('order_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('medication_slot')) has-error @endif'>
        <label for='medication_slot' class='col-sm-2 control-label'>medication_slot<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('medication_slot', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('medication_slot')) <p class="help-block">{{ $errors->first('medication_slot') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('medication_datetime')) has-error @endif'>
        <label for='medication_datetime' class='col-sm-2 control-label'>medication_datetime<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('medication_datetime', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('medication_datetime')) <p class="help-block">{{ $errors->first('medication_datetime') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/medication_records" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
-->
