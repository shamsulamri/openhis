
    <div class='form-group'>
        <label for='order' class='col-sm-3 control-label'>Product</span></label>
        <div class='col-sm-9'>
            {{ Form::label('order', $order_stop->order->product->product_name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
        </div>
    </div>

    <div class='form-group'>
        <label for='order' class='col-sm-3 control-label'>Ordered By</span></label>
        <div class='col-sm-9'>
            {{ Form::label('order', $order_stop->order->user->name, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/admission_tasks" role="button">Cancel</a>
            {{ Form::submit('Stop', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	
    {{ Form::hidden('order_id', $order_stop->order_id) }}
    {{ Form::hidden('user_id', $order_stop->user_id) }}
