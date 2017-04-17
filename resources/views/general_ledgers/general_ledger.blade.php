
    <div class='form-group  @if ($errors->has('gl_name')) has-error @endif'>
        <label for='gl_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('gl_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('gl_name')) <p class="help-block">{{ $errors->first('gl_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/general_ledgers" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
