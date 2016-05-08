
<h4>
<ul class="nav nav-tabs nav-justified">
  <li role="presentation"><a href="/encounters/{{ $encounter->encounter_id }}/edit">Step 1: Encounter</a></li>
  <li role="presentation" class="active"><a href="#">Final: Queue</a></li>
</ul>
</h4>
<br>
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
