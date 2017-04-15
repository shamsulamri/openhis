
    <div class='form-group  @if ($errors->has('team_code')) has-error @endif'>
        <label for='team_code' class='col-sm-2 control-label'>team_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('team_code', $team,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('team_code')) <p class="help-block">{{ $errors->first('team_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('username')) has-error @endif'>
        <label for='username' class='col-sm-2 control-label'>username<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('username', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('username')) <p class="help-block">{{ $errors->first('username') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/team_members" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
