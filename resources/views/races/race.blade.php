
    <div class='form-group  @if ($errors->has('race_name')) has-error @endif'>
        <label for='race_name' class='col-sm-2 control-label'>race_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('race_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('race_name')) <p class="help-block">{{ $errors->first('race_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/races" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
