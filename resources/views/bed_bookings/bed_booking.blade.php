    <div class='form-group  @if ($errors->has('book_date')) has-error @endif'>
        <label for='book_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="book_date" id="book_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($bed_booking->book_date) }}">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
				@if ($errors->has('book_date')) <p class="help-block">{{ $errors->first('book_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        <label for='class_code' class='col-sm-3 control-label'>Ward Class<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('book_description')) has-error @endif'>
        {{ Form::label('book_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('book_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('book_description')) <p class="help-block">{{ $errors->first('book_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@can('module-ward')
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
			@endcan
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('patient_id', $patient->patient_id) }}
	{{ Form::hidden('admission_id', $admission_id ) }}

	<script>
		$('#book_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	</script>
