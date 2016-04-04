
    <div class='form-group  @if ($errors->has('set_name')) has-error @endif'>
        <label for='set_name' class='col-sm-2 control-label'>set_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('set_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('set_name')) <p class="help-block">{{ $errors->first('set_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/sets" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
