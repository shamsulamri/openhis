
    <div class='form-group  @if ($errors->has('block_code')) has-error @endif'>
        {{ Form::label('block_code', 'Type',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ Form::select('block_code', $blocks, null, ['class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_name')) has-error @endif'>
        {{ Form::label('block_name', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('block_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('block_name')) <p class="help-block">{{ $errors->first('block_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('service_id')) has-error @endif'>
        {{ Form::label('service_id', 'Service',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ Form::select('service_id', $services, null, ['class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_date')) has-error @endif'>
        <label for='block_date' class='col-sm-3 control-label'>Date Start<span style='color:red;'> *</span></label>
        <div class='col-sm-4'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="block_date" id="block_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($block_date->block_date) }}">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
            @if ($errors->has('block_date')) <p class="help-block">{{ $errors->first('block_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_date_end')) has-error @endif'>
        <label for='block_date_end' class='col-sm-3 control-label'>Date End</label>
        <div class='col-sm-4'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="block_date_end" id="block_date_end" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($block_date->block_date_end) }}">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
            @if ($errors->has('block_date_end')) <p class="help-block">{{ $errors->first('block_date_end') }}</p> @endif
        </div>
    </div>


    <div class='form-group'>
        <label for='block_date' class='col-sm-3 control-label'>Slot Time</label>
		<div class='col-sm-2'>
				<div id="block_time_start" name="block_time_start" class="input-group clockpicker @if ($errors->has('block_time_start')) has-error @endif" data-autoclose="true">
						{{ Form::text('block_time_start', null, ['placeholder'=>'Start','class'=>'form-control','data-mask'=>'99:99']) }}
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
				</div>
				@if ($errors->has('block_time_start')) 
				<div class='has-error'>
				<p class="help-block">{{ $errors->first('block_time_start') }}</p> 
				</div>
				@endif
		</div>
        <div class='col-sm-2'>
				<div id="block_time_end" name="block_time_end" class="input-group clockpicker @if ($errors->has('block_time_end')) has-error @endif" data-autoclose="true">
						{{ Form::text('block_time_end', null, ['placeholder'=>'End','class'=>'form-control','data-mask'=>'99:99']) }}
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
				</div>
				@if ($errors->has('block_time_end')) 
				<div class='has-error'>
				<p class="help-block">{{ $errors->first('block_time_end') }}</p> 
				</div>
				@endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_weekly')) has-error @endif'>
        {{ Form::label('block_recur_weekly', 'Recurring',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ Form::radio('block_recur',0, $block_date->block_recur==0) }} 
			<label>None</label>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_annually')) has-error @endif'>
        {{ Form::label('', '',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9 col-md-offset-3'>
			{{ Form::radio('block_recur',1, $block_date->block_recur==1) }}
			<label>Annually</label>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_monthly')) has-error @endif'>
        {{ Form::label('', '',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9 col-md-offset-3'>
			{{ Form::radio('block_recur',2, $block_date->block_recur==2) }}
			<label>Monthly</label>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_weekly')) has-error @endif'>
        {{ Form::label('', '',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9 col-md-offset-3'>
			{{ Form::radio('block_recur',3, $block_date->block_recur==3) }}
			<label>Weekly</label>
        </div>
    </div>


    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/block_dates" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<script>
				$('#block_date').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
				});

				$('#block_date_end').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
				});
				$('.clockpicker').clockpicker();
	</script>
