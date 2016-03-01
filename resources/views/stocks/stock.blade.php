
    <div class='form-group  @if ($errors->has('line_id')) has-error @endif'>
        {{ Form::label('line_id', 'line_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('line_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('line_id')) <p class="help-block">{{ $errors->first('line_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>move_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('move_code', $move,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('store_code', 'store_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_date')) has-error @endif'>
        <label for='stock_date' class='col-sm-2 control-label'>stock_date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('stock_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('stock_date')) <p class="help-block">{{ $errors->first('stock_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_quantity')) has-error @endif'>
        <label for='stock_quantity' class='col-sm-2 control-label'>stock_quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('stock_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('stock_quantity')) <p class="help-block">{{ $errors->first('stock_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_description')) has-error @endif'>
        {{ Form::label('stock_description', 'stock_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('stock_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('stock_description')) <p class="help-block">{{ $errors->first('stock_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_batch_number')) has-error @endif'>
        {{ Form::label('stock_batch_number', 'stock_batch_number',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('stock_batch_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('stock_batch_number')) <p class="help-block">{{ $errors->first('stock_batch_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stocks" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
