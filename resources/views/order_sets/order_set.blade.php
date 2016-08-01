
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='set_code' class='col-sm-3 control-label'>set_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('set_code', $set,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('set_code')) <p class="help-block">{{ $errors->first('set_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-3 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/order_sets" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
