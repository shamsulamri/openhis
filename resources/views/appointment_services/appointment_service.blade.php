
    <div class='form-group  @if ($errors->has('service_name')) has-error @endif'>
        {{ Form::label('service_name', 'Service Name',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('service_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('service_name')) <p class="help-block">{{ $errors->first('service_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('department_code')) has-error @endif'>
        <label for='department_code' class='col-sm-3 control-label'>Department<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('department_code', $department,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_start')) has-error @endif'>
        <label for='service_start' class='col-sm-3 control-label'>Time Start<span style='color:red;'> *</span></label>
        <div class='col-sm-3'>
			<div id="service_start" name="service_start" class="input-group clockpicker" data-autoclose="true">
				{{ Form::text('service_start', null, ['class'=>'form-control','data-mask'=>'99:99']) }}
				<span class="input-group-addon">
					<span class="fa fa-clock-o"></span>
				</span>
			</div>
			@if ($errors->has('service_start')) <p class="help-block">{{ $errors->first('service_start') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_end')) has-error @endif'>
        <label for='service_end' class='col-sm-3 control-label'>Time End<span style='color:red;'> *</span></label>
        <div class='col-sm-3'>
			<div id="service_end" name="service_end" class="input-group clockpicker" data-autoclose="true">
				{{ Form::text('service_end', null, ['class'=>'form-control','data-mask'=>'99:99']) }}
				<span class="input-group-addon">
					<span class="fa fa-clock-o"></span>
				</span>
			</div>
			@if ($errors->has('service_end')) <p class="help-block">{{ $errors->first('service_end') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('service_cease')) has-error @endif'>
		{{ Form::label('Cease Date', 'Cease Date',['class'=>'col-md-3 control-label']) }}
		<div class='col-md-3'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="service_cease" id="service_cease" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($appointment_service->service_cease) }}"> 
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>
	</div>

    <div class='form-group  @if ($errors->has('service_duration')) has-error @endif'>
        {{ Form::label('service_duration', 'Slot Duration',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('service_duration', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('service_duration')) <p class="help-block">{{ $errors->first('service_duration') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_monday')) has-error @endif'>
        {{ Form::label('service_monday', 'Monday',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('service_monday', '1') }}
            @if ($errors->has('service_monday')) <p class="help-block">{{ $errors->first('service_monday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_tuesday')) has-error @endif'>
        {{ Form::label('service_tuesday', 'Tuesday',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('service_tuesday', '1') }}
            @if ($errors->has('service_tuesday')) <p class="help-block">{{ $errors->first('service_tuesday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_wednesday')) has-error @endif'>
        {{ Form::label('service_wednesday', 'Wednesday',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('service_wednesday', '1') }}
            @if ($errors->has('service_wednesday')) <p class="help-block">{{ $errors->first('service_wednesday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_thursday')) has-error @endif'>
        {{ Form::label('service_thursday', 'Thursday',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('service_thursday', '1') }}
            @if ($errors->has('service_thursday')) <p class="help-block">{{ $errors->first('service_thursday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_friday')) has-error @endif'>
        {{ Form::label('service_friday', 'Friday',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('service_friday', '1') }}
            @if ($errors->has('service_friday')) <p class="help-block">{{ $errors->first('service_friday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_saturday')) has-error @endif'>
        {{ Form::label('service_saturday', 'Saturday',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('service_saturday', '1') }}
            @if ($errors->has('service_saturday')) <p class="help-block">{{ $errors->first('service_saturday') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_sunday')) has-error @endif'>
        {{ Form::label('service_sunday', 'Sunday',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('service_sunday', '1') }}
            @if ($errors->has('service_sunday')) <p class="help-block">{{ $errors->first('service_sunday') }}</p> @endif
        </div>
    </div>


	<!--
    <div class='form-group  @if ($errors->has('service_block_dates')) has-error @endif'>
        {{ Form::label('service_block_dates', 'Block Dates',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('service_block_dates', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('service_block_dates')) <p class="help-block">{{ $errors->first('service_block_dates') }}</p> @endif
        </div>
    </div>
	-->

    <div class='form-group  @if ($errors->has('service_status')) has-error @endif'>
        <label for='service_status' class='col-sm-3 control-label'>Status<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			{{ Form::select('service_status', ['1'=>'Active', '2'=>'Suspend', '99'=>'Inactive'], null, ['class'=>'form-control']) }}
            @if ($errors->has('service_status')) <p class="help-block">{{ $errors->first('service_status') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@if ($type=='user') 
            <a class="btn btn-default" href="/user_profile" role="button">Cancel</a>
			@else
            <a class="btn btn-default" href="/appointment_services" role="button">Cancel</a>
			@endif

            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('type', $type) }}
	<script>

		$(function(){
				$('#service_start').combodate({
						format: "HH:mm",
						template: "HH : mm",
						value: '{{ $appointment_service->service_start }}',
						minuteStep: 1,
						customClass: 'select'
				});    
		});

		$(function(){
				$('#service_end').combodate({
						format: "HH:mm",
						template: "HH : mm",
						value: '{{ $appointment_service->service_end }}',
						minuteStep: 1,
						customClass: 'select'
				});    
		});

		$('.clockpicker').clockpicker();
		$('#service_cease').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
				});
	</script>
