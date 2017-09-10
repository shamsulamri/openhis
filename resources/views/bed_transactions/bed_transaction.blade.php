
    <div class='form-group  @if ($errors->has('transaction_name')) has-error @endif'>
        <label for='transaction_name' class='col-sm-2 control-label'>transaction_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('transaction_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('transaction_name')) <p class="help-block">{{ $errors->first('transaction_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bed_transactions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
