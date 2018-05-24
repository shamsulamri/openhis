
    <div class='form-group  @if ($errors->has('set_name')) has-error @endif'>
        <label for='set_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('set_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('set_name')) <p class="help-block">{{ $errors->first('set_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-3 control-label'>Owner</label>
        <div class='col-sm-9'>
            {{ Form::select('user_id', $consultants,null, ['class'=>'form-control']) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/sets" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
