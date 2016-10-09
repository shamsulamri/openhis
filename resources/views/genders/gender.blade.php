
    <div class='form-group  @if ($errors->has('gender_name')) has-error @endif'>
        <label for='gender_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('gender_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('gender_name')) <p class="help-block">{{ $errors->first('gender_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/genders" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
