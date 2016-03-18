
    <div class='form-group  @if ($errors->has('arrival_description')) has-error @endif'>
        {{ Form::label('arrival_description', 'arrival_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('arrival_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('arrival_description')) <p class="help-block">{{ $errors->first('arrival_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
            {{ Form::hidden('encounter_id', $ward_arrival->encounter_id, ['class'=>'form-control','placeholder'=>'',]) }}
