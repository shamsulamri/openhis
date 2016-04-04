
    <div class='form-group  @if ($errors->has('birth_name')) has-error @endif'>
        <label for='birth_name' class='col-sm-2 control-label'>birth_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('birth_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('birth_name')) <p class="help-block">{{ $errors->first('birth_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/birth_types" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>