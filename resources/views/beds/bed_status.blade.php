
    <div class='form-group  @if ($errors->has('bed_name')) has-error @endif'>
        <label for='bed_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('bed_name', $bed->bed_name, ['class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('status_code')) has-error @endif'>
        {{ Form::label('status_code', 'Status',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('status_code', $status,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/beds" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('bed_name', $bed->bed_name) }}
	{{ Form::hidden('ward_code', $bed->ward_code) }}
	{{ Form::hidden('encounter_code', $bed->encounter_code) }}
	{{ Form::hidden('class_code', $bed->class_code) }}
