
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>Unit of Measure</label>
        <div class='col-sm-10'>
            {{ Form::label('unit', $product->unitMeasure->unit_name, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('amount_current')) has-error @endif'>
        <label for='amount_current' class='col-sm-2 control-label'>On-Hand</label>
        <div class='col-sm-10'>
            {{ Form::label('0', $stock_input_line->line_pre_quantity, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('amount_current')) <p class="help-block">{{ $errors->first('amount_current') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_average_cost')) has-error @endif'>
        <label for='product_average_cost' class='col-sm-2 control-label'>Average Cost</label>
        <div class='col-sm-10'>
            {{ Form::label('product_average_cost', $product->product_average_cost, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    {{ Form::hidden('amount_current', $stock_input_line->amount_current, ['class'=>'form-control','placeholder'=>'',]) }}

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_inputs/input/{{ $stock_input_line->input_id }}" role="button">Back</a>
			<!--
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			-->
        </div>
    </div>
