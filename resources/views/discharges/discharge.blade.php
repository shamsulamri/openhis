
    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        <label for='type_code' class='col-sm-3 control-label'>Estimated Cost</label>
        <div class='col-sm-9'>
        	{{ Form::label('estimated_cost', number_format($estimated_cost,2),['class'=>'col-sm-3 form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        <label for='type_code' class='col-sm-3 control-label'>Outcome<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('type_code', $type,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('type_code')) <p class="help-block">{{ $errors->first('type_code') }}</p> @endif
        </div>
    </div>

	@if ($consultation->encounter->encounter_code=='inpatient')
    <div class='form-group  @if ($errors->has('discharge_diagnosis')) has-error @endif'>
        {{ Form::label('discharge_diagnosis', 'Diagnosis',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('discharge_diagnosis', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_diagnosis')) <p class="help-block">{{ $errors->first('discharge_diagnosis') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('discharge_summary')) has-error @endif'>
        {{ Form::label('discharge_summary', 'Summary',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('discharge_summary', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_summary')) <p class="help-block">{{ $errors->first('discharge_summary') }}</p> @endif
        </div>
    </div>
	@else

    <div class='form-group  @if ($errors->has('discharge_summary')) has-error @endif'>
        {{ Form::label('discharge_summary', 'Summary',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('discharge_summary', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_summary')) <p class="help-block">{{ $errors->first('discharge_summary') }}</p> @endif
        </div>
    </div>
	@endif


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('discharge_date')) has-error @endif'>
						<label for='discharge_date' class='col-sm-6 control-label'>Date<span style='color:red;'> *</span></label>
						<div class='col-sm-6'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="discharge_date" id="discharge_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($discharge->discharge_date) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('discharge_date')) <p class="help-block">{{ $errors->first('discharge_date') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('discharge_time')) has-error @endif'>
						<label for='discharge_time' class='col-sm-6 control-label'>Time End</label>
						<div class='col-sm-6'>
							<div id="discharge_time" name="discharge_time" class="input-group clockpicker" data-autoclose="true">
								{{ Form::text('discharge_time', null, ['class'=>'form-control','data-mask'=>'99:99']) }}
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
							</div>
							@if ($errors->has('discharge_time')) <p class="help-block">{{ $errors->first('discharge_time') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	@if ($consultation->encounter->encounter_code != 'mortuary')
    <div class='form-group'>
        {{ Form::label('mc', 'Medical Certificate',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9 control-label'>
			<div align='left'>
		@if ($mc)
        		{{ Form::label('product', DojoUtility::dateLongFormat($mc->mc_start),['class'=>'control-label']) }}
				@if (!empty($mc->mc_end))
        		{{ Form::label('mc', ' - '.DojoUtility::dateLongFormat($mc->mc_end),['class'=>'control-label']) }}
				@endif
				@if (!empty($mc->mc_time_start))
				<br>
        		{{ Form::label('mc', 'Time: '.$mc->mc_time_start,['class'=>'control-label']) }} - 
        		{{ Form::label('mc', $mc->mc_time_end,['class'=>'control-label']) }}
				@endif
				<br>
        		{{ Form::label('mc', 'Serial Number: '.$mc->mc_id,['class'=>'control-label']) }}
				@else
				<span class='label label-warning'>No medical certificate.</span>
		@endif
        	</div>
        </div>
    </div>
	@endif

    <div class='form-group'>
        {{ Form::label('discharge_orders', 'Discharge Orders',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9 control-label'>
				<div align='left'>
		@if (count($discharge_orders)>0)
			@foreach ($discharge_orders as $order)
        		{{ Form::label('product', "- ".strtoupper($order->product_name)) }}<br>
			@endforeach
			<br>
		@else
					<span class='label label-warning'>No discharge order.</span>
		@endif
				</div>
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Discharge Patient', ['class'=>'btn btn-primary']) }}
        </div>
    </div>


	{{ Form::hidden('user_id', null) }}
	{{ Form::hidden('consultation_id', null) }}
	{{ Form::hidden('encounter_id', null) }}

	<script>
		$('#discharge_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		$('.clockpicker').clockpicker();

		$(function(){
				$('#discharge_time').combodate({
						format: "HH:mm",
						template: "HH : mm",
						value: '{{ $discharge->discharge_time }}',
						minuteStep: 1,
						customClass: 'select'
				});    
		});
	</script>
