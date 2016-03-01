
    <div class='form-group  @if ($errors->has('purchase_id')) has-error @endif'>
        <label for='purchase_id' class='col-sm-2 control-label'>purchase_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('purchase_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('purchase_id')) <p class="help-block">{{ $errors->first('purchase_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('line_quantity_ordered')) has-error @endif'>
        {{ Form::label('line_quantity_ordered', 'line_quantity_ordered',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('line_quantity_ordered', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('line_quantity_ordered')) <p class="help-block">{{ $errors->first('line_quantity_ordered') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('line_quantity_received')) has-error @endif'>
        {{ Form::label('line_quantity_received', 'line_quantity_received',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('line_quantity_received', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('line_quantity_received')) <p class="help-block">{{ $errors->first('line_quantity_received') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('line_price')) has-error @endif'>
        {{ Form::label('line_price', 'line_price',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('line_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('line_price')) <p class="help-block">{{ $errors->first('line_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('line_batch_number')) has-error @endif'>
        {{ Form::label('line_batch_number', 'line_batch_number',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('line_batch_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('line_batch_number')) <p class="help-block">{{ $errors->first('line_batch_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('line_expiry_date')) has-error @endif'>
        <label for='line_expiry_date' class='col-sm-2 control-label'>line_expiry_date</label>
        <div class='col-sm-10'>
            {{ Form::text('line_expiry_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('line_expiry_date')) <p class="help-block">{{ $errors->first('line_expiry_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('line_total')) has-error @endif'>
        {{ Form::label('line_total', 'line_total',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('line_total', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('line_total')) <p class="help-block">{{ $errors->first('line_total') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('line_quantity_received_2')) has-error @endif'>
        {{ Form::label('line_quantity_received_2', 'line_quantity_received_2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('line_quantity_received_2', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('line_quantity_received_2')) <p class="help-block">{{ $errors->first('line_quantity_received_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/purchase_order_lines" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
