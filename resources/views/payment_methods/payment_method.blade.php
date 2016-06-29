
    <div class='form-group  @if ($errors->has('payment_name')) has-error @endif'>
        <label for='payment_name' class='col-sm-2 control-label'>payment_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('payment_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('payment_name')) <p class="help-block">{{ $errors->first('payment_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/payment_methods" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>