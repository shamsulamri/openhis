    <div class='form-group'>
        <label for='order' class='col-sm-3 control-label'>Order</span></label>
        <div class='col-sm-9'>
            {{ Form::text('order', $order_cancellation->order->product->product_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('cancel_reason')) has-error @endif'>
        <label for='cancel_reason' class='col-sm-3 control-label'>Reason<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::textarea('cancel_reason', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('cancel_reason')) <p class="help-block">{{ $errors->first('cancel_reason') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/orders" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

    {{ Form::hidden('order_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
