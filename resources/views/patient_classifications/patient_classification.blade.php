
    <div class='form-group  @if ($errors->has('classification_name')) has-error @endif'>
        <label for='classification_name' class='col-sm-2 control-label'>classification_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('classification_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('classification_name')) <p class="help-block">{{ $errors->first('classification_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/patient_classifications" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
