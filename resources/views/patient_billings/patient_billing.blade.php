
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-3 control-label'>encounter_id<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('encounter_id')) <p class="help-block">{{ $errors->first('encounter_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_id')) has-error @endif'>
        <label for='order_id' class='col-sm-3 control-label'>order_id<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('order_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_id')) <p class="help-block">{{ $errors->first('order_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-3 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tax_code')) has-error @endif'>
        <label for='tax_code' class='col-sm-3 control-label'>tax_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('tax_code', $tax,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('tax_code')) <p class="help-block">{{ $errors->first('tax_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tax_rate')) has-error @endif'>
        <label for='tax_rate' class='col-sm-3 control-label'>tax_rate<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('tax_rate', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('tax_rate')) <p class="help-block">{{ $errors->first('tax_rate') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_discount')) has-error @endif'>
        <label for='bill_discount' class='col-sm-3 control-label'>bill_discount<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_discount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_discount')) <p class="help-block">{{ $errors->first('bill_discount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_quantity')) has-error @endif'>
        <label for='bill_quantity' class='col-sm-3 control-label'>bill_quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_quantity')) <p class="help-block">{{ $errors->first('bill_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_unit_price')) has-error @endif'>
        <label for='bill_unit_price' class='col-sm-3 control-label'>bill_unit_price<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_unit_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_unit_price')) <p class="help-block">{{ $errors->first('bill_unit_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_total')) has-error @endif'>
        <label for='bill_total' class='col-sm-3 control-label'>bill_total<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_total', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_total')) <p class="help-block">{{ $errors->first('bill_total') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_exempted')) has-error @endif'>
        <label for='bill_exempted' class='col-sm-3 control-label'>bill_exempted<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::checkbox('bill_exempted', '1') }}
            @if ($errors->has('bill_exempted')) <p class="help-block">{{ $errors->first('bill_exempted') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/patient_billings" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
