
    <div class='form-group  @if ($errors->has('order_id')) has-error @endif'>
        <label for='order_id' class='col-sm-2 control-label'>order_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_id')) <p class="help-block">{{ $errors->first('order_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('cancel_reason')) has-error @endif'>
        <label for='cancel_reason' class='col-sm-2 control-label'>cancel_reason<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('cancel_reason', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('cancel_reason')) <p class="help-block">{{ $errors->first('cancel_reason') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/order_cancellations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
