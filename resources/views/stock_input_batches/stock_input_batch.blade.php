
    <div class='form-group  @if ($errors->has('input_id')) has-error @endif'>
        <label for='input_id' class='col-sm-2 control-label'>input_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('input_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('input_id')) <p class="help-block">{{ $errors->first('input_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_number')) has-error @endif'>
        <label for='batch_number' class='col-sm-2 control-label'>batch_number<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('batch_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('batch_number')) <p class="help-block">{{ $errors->first('batch_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('expiry_date')) has-error @endif'>
        <label for='expiry_date' class='col-sm-2 control-label'>expiry_date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('expiry_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('expiry_date')) <p class="help-block">{{ $errors->first('expiry_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_quantity')) has-error @endif'>
        <label for='batch_quantity' class='col-sm-2 control-label'>batch_quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('batch_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('batch_quantity')) <p class="help-block">{{ $errors->first('batch_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_input_batches" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
