
    <div class='form-group  @if ($errors->has('block_name')) has-error @endif'>
        {{ Form::label('block_name', 'Name',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('block_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('block_name')) <p class="help-block">{{ $errors->first('block_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_date')) has-error @endif'>
        <label for='block_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="block_date" id="block_date" type="text" class="form-control" value="{{ $block_date->block_date }}">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
            @if ($errors->has('block_date')) <p class="help-block">{{ $errors->first('block_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_weekly')) has-error @endif'>
        {{ Form::label('block_recur_weekly', 'Recurring',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			{{ Form::radio('block_recur',0, $block_date->block_recur==0) }} 
			<label>None</label><br>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_annually')) has-error @endif'>
        <div class='col-sm-9 col-md-offset-3'>
			{{ Form::radio('block_recur',1, $block_date->block_recur==1) }}
			<label>Annually</label>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_monthly')) has-error @endif'>
        <div class='col-sm-9 col-md-offset-3'>
			{{ Form::radio('block_recur',2, $block_date->block_recur==2) }}
			<label>Monthly</label>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('block_recur_weekly')) has-error @endif'>
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
	</script>
