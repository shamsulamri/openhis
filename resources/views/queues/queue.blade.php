
    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
		<label for='location_code' class='col-sm-2 control-label'>Queue<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('location_code', $location,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/encounters/{{ $queue->encounter_id }}/edit" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('encounter_id', null) }}
