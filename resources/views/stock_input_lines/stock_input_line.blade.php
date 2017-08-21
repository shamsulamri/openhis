
	<!--
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
	-->

    <div class='form-group  @if ($errors->has('amount_current')) has-error @endif'>
        <label for='amount_current' class='col-sm-2 control-label'>On-Hand</label>
        <div class='col-sm-10'>
            {{ Form::label('amount_current', $stock_input_line->getOnHand(), ['class'=>'form-control','placeholder'=>'',]) }}
            {{ Form::hidden('amount_current', $stock_input_line->amount_current, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('amount_current')) <p class="help-block">{{ $errors->first('amount_current') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_number')) has-error @endif'>
        <label for='batch_number' class='col-sm-2 control-label'>Batch Number @if ($product->product_track_batch) <span style='color:red;'> *</span> @endif</label>
        <div class='col-sm-10'>
            {{ Form::text('batch_number', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('batch_number')) <p class="help-block">{{ $errors->first('batch_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('amount_new')) has-error @endif'>
        <label for='amount_new' class='col-sm-2 control-label'>Transaction Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('amount_new', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('amount_new')) <p class="help-block">{{ $errors->first('amount_new') }}</p> @endif
        </div>
    </div>

	<!--
    <div class='form-group  @if ($errors->has('amount_difference')) has-error @endif'>
        <label for='amount_difference' class='col-sm-2 control-label'>amount_difference<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('amount_difference', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('amount_difference')) <p class="help-block">{{ $errors->first('amount_difference') }}</p> @endif
        </div>
    </div>
	-->

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_inputs/input/{{ $stock_input_line->input_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
