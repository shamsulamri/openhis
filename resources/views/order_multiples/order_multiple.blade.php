
    <div class='form-group  @if ($errors->has('order_id')) has-error @endif'>
        <label for='order_id' class='col-sm-2 control-label'>order_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_id')) <p class="help-block">{{ $errors->first('order_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_completed')) has-error @endif'>
        <label for='order_completed' class='col-sm-2 control-label'>order_completed<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::checkbox('order_completed', '1') }}
            @if ($errors->has('order_completed')) <p class="help-block">{{ $errors->first('order_completed') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/order_multiples" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
