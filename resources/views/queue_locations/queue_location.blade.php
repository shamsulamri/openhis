
    <div class='form-group  @if ($errors->has('location_name')) has-error @endif'>
        <label for='location_name' class='col-sm-2 control-label'>location_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('location_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('location_name')) <p class="help-block">{{ $errors->first('location_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        {{ Form::label('user_id', 'user_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('encounter_code')) has-error @endif'>
        {{ Form::label('Encounter Type', 'Encounter Type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('encounter_code', $encounter_type, null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/queue_locations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
