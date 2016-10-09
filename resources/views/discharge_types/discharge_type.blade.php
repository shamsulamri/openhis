
    <div class='form-group  @if ($errors->has('type_name')) has-error @endif'>
        <label for='type_name' class='col-sm-3 control-label'>Discharge Type<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('type_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('type_name')) <p class="help-block">{{ $errors->first('type_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/discharge_types" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
