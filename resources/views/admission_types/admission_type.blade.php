
    <div class='form-group  @if ($errors->has('admission_name')) has-error @endif'>
        <label for='admission_name' class='col-sm-2 control-label'>admission_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('admission_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('admission_name')) <p class="help-block">{{ $errors->first('admission_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/admission_types" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
