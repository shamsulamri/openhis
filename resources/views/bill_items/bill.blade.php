
    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        {{ Form::label('product_name', 'Product',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('product_name', $bill->bill_name, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_name')) <p class="help-block">{{ $errors->first('product_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_unit_price')) has-error @endif'>
        {{ Form::label('bill_unit_price', 'Unit Price',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($bill->product->category_code = 'bed')
            {{ Form::text('bill_unit_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
			@else
            {{ Form::label('bill_unit_price', empty($bill->bill_unit_price)?'-':$bill->bill_unit_price, ['class'=>'form-control','placeholder'=>'',]) }}
			@endif
            @if ($errors->has('bill_unit_price')) <p class="help-block">{{ $errors->first('bill_unit_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_quantity')) has-error @endif'>
        {{ Form::label('bill_quantity', 'Quantity',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($bill->product->category_code = 'bed')
            {{ Form::text('bill_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
			@else 
            {{ Form::label('bill_quantity', empty($bill->bill_quantity)?'-':$bill->bill_quantity, ['class'=>'form-control','placeholder'=>'',]) }}
			@endif

            @if ($errors->has('bill_quantity')) <p class="help-block">{{ $errors->first('bill_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_discount')) has-error @endif'>
        {{ Form::label('bill_discount', 'Discount',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('bill_discount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_discount')) <p class="help-block">{{ $errors->first('bill_discount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_markup')) has-error @endif'>
        {{ Form::label('bill_markup', 'Markup',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('bill_markup', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_markup')) <p class="help-block">{{ $errors->first('bill_markup') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
        {{ Form::label('order_exempterd', 'Exempted',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('bill_exempted', '1',null,['class'=>'i-checks']) }}
            @if ($errors->has('bill_exempted')) <p class="help-block">{{ $errors->first('bill_exempted') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_description')) has-error @endif'>
        {{ Form::label('bill_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('bill_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'3']) }}
            @if ($errors->has('bill_description')) <p class="help-block">{{ $errors->first('bill_description') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/bill_items/{{ $bill->encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
