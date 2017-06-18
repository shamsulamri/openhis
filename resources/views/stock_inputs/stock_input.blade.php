
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('amount_current')) has-error @endif'>
        <label for='amount_current' class='col-sm-2 control-label'>amount_current<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('amount_current', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('amount_current')) <p class="help-block">{{ $errors->first('amount_current') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('amount_new')) has-error @endif'>
        <label for='amount_new' class='col-sm-2 control-label'>amount_new<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('amount_new', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('amount_new')) <p class="help-block">{{ $errors->first('amount_new') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('amount_difference')) has-error @endif'>
        <label for='amount_difference' class='col-sm-2 control-label'>amount_difference<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('amount_difference', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('amount_difference')) <p class="help-block">{{ $errors->first('amount_difference') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_inputs" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
