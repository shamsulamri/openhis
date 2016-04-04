
    <div class='form-group  @if ($errors->has('organisation_name')) has-error @endif'>
        <label for='organisation_name' class='col-sm-2 control-label'>organisation_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('organisation_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('organisation_name')) <p class="help-block">{{ $errors->first('organisation_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/care_organisations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>