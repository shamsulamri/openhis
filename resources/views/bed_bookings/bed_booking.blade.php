    <div class='form-group  @if ($errors->has('book_date')) has-error @endif'>
        <label for='book_date' class='col-sm-2 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			<input id="book_date" name="book_date" type="text">
            @if ($errors->has('book_date')) <p class="help-block">{{ $errors->first('book_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        <label for='class_code' class='col-sm-2 control-label'>Ward Class<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bed_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-2 control-label'>Bed</label>
        <div class='col-sm-10'>
            {{ Form::select('bed_code', $bed,$bed_booking->bed_code, ['id'=>'bed_code','class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('book_description')) has-error @endif'>
        {{ Form::label('book_description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('book_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('book_description')) <p class="help-block">{{ $errors->first('book_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
			@can('module-patient')
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Return</a>
			@endcan
			@can('module-ward')
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
			@endcan
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('patient_id', $patient->patient_id) }}
	{{ Form::hidden('admission_id', $admission_id ) }}
	{{ Form::hidden('bed', $bed_booking->bed_code ) }}

<script>
	document.getElementById('bed_code').disabled = true;
	</script>
	<script>
		$(function(){
				$('#book_date').combodate({
						format: "DD/MM/YYYY HH:mm",
						template: "DD MMMM YYYY     HH : mm",
						value: '{{ $bed_booking->book_date }}',
						maxYear: '{{ \Carbon\Carbon::now()->year+5 }}',
						minYear: '{{ \Carbon\Carbon::now()->year }}',
						customClass: 'select'
				});    
		});
	</script>
