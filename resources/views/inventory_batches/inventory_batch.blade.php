
    <div class='form-group  @if ($errors->has('batch_number')) has-error @endif'>
        <label for='batch_number' class='col-sm-2 control-label'>batch_number<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('batch_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('batch_number')) <p class="help-block">{{ $errors->first('batch_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_expiry_date')) has-error @endif'>
        {{ Form::label('batch_expiry_date', 'batch_expiry_date',['class'=>'col-md-2 control-label']) }}
        <div class='col-md-10'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="batch_expiry_date" id="batch_expiry_date" type="text" class="form-control">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_description')) has-error @endif'>
        {{ Form::label('batch_description', 'batch_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('batch_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('batch_description')) <p class="help-block">{{ $errors->first('batch_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/inventory_batches" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
<script>
		$('#batch_expiry_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
</script>
