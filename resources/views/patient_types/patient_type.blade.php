
    <div class='form-group  @if ($errors->has('type_name')) has-error @endif'>
        <label for='type_name' class='col-sm-2 control-label'>type_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('type_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('type_name')) <p class="help-block">{{ $errors->first('type_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/patient_types" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
