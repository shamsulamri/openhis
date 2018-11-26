
    <div class='form-group  @if ($errors->has('entitlement_name')) has-error @endif'>
        <label for='entitlement_name' class='col-sm-2 control-label'>entitlement_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('entitlement_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('entitlement_name')) <p class="help-block">{{ $errors->first('entitlement_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/entitlements" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
