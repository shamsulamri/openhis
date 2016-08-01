
    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>product_name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('product_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('product_name')) <p class="help-block">{{ $errors->first('product_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
        <label for='category_code' class='col-sm-3 control-label'>category_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('category_code', $category,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
        {{ Form::label('unit_code', 'unit_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('unit_code', $unit,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_form')) has-error @endif'>
        <label for='order_form' class='col-sm-3 control-label'>order_form<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('order_form', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('order_form')) <p class="help-block">{{ $errors->first('order_form') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_upc')) has-error @endif'>
        {{ Form::label('product_upc', 'product_upc',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_upc', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('product_upc')) <p class="help-block">{{ $errors->first('product_upc') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_sku')) has-error @endif'>
        {{ Form::label('product_sku', 'product_sku',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_sku', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('product_sku')) <p class="help-block">{{ $errors->first('product_sku') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_active')) has-error @endif'>
        {{ Form::label('product_active', 'product_active',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_active', '1') }}
            @if ($errors->has('product_active')) <p class="help-block">{{ $errors->first('product_active') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_drop_shipment')) has-error @endif'>
        {{ Form::label('product_drop_shipment', 'product_drop_shipment',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_drop_shipment', '1') }}
            @if ($errors->has('product_drop_shipment')) <p class="help-block">{{ $errors->first('product_drop_shipment') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_stocked')) has-error @endif'>
        {{ Form::label('product_stocked', 'product_stocked',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_stocked', '1') }}
            @if ($errors->has('product_stocked')) <p class="help-block">{{ $errors->first('product_stocked') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_purchased')) has-error @endif'>
        {{ Form::label('product_purchased', 'product_purchased',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_purchased', '1') }}
            @if ($errors->has('product_purchased')) <p class="help-block">{{ $errors->first('product_purchased') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_sold')) has-error @endif'>
        {{ Form::label('product_sold', 'product_sold',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_sold', '1') }}
            @if ($errors->has('product_sold')) <p class="help-block">{{ $errors->first('product_sold') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_sale_price')) has-error @endif'>
        {{ Form::label('product_sale_price', 'product_sale_price',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_sale_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_sale_price')) <p class="help-block">{{ $errors->first('product_sale_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_discontinued')) has-error @endif'>
        {{ Form::label('product_discontinued', 'product_discontinued',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_discontinued', '1') }}
            @if ($errors->has('product_discontinued')) <p class="help-block">{{ $errors->first('product_discontinued') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_guarantee_days')) has-error @endif'>
        {{ Form::label('product_guarantee_days', 'product_guarantee_days',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_guarantee_days', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_guarantee_days')) <p class="help-block">{{ $errors->first('product_guarantee_days') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_bom')) has-error @endif'>
        {{ Form::label('product_bom', 'product_bom',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_bom', '1') }}
            @if ($errors->has('product_bom')) <p class="help-block">{{ $errors->first('product_bom') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_reorder')) has-error @endif'>
        {{ Form::label('product_reorder', 'product_reorder',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_reorder', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_reorder')) <p class="help-block">{{ $errors->first('product_reorder') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_purchase_price')) has-error @endif'>
        {{ Form::label('product_purchase_price', 'product_purchase_price',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_purchase_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_purchase_price')) <p class="help-block">{{ $errors->first('product_purchase_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        {{ Form::label('location_code', 'location_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('location_code', $location,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('form_code')) has-error @endif'>
        {{ Form::label('form_code', 'form_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('form_code', $form,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('form_code')) <p class="help-block">{{ $errors->first('form_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_average_cost')) has-error @endif'>
        {{ Form::label('product_average_cost', 'product_average_cost',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_average_cost', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_average_cost')) <p class="help-block">{{ $errors->first('product_average_cost') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_units')) has-error @endif'>
        {{ Form::label('product_units', 'product_units',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_units', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_units')) <p class="help-block">{{ $errors->first('product_units') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_sale_margin')) has-error @endif'>
        {{ Form::label('product_sale_margin', 'product_sale_margin',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_sale_margin', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_sale_margin')) <p class="help-block">{{ $errors->first('product_sale_margin') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_gst')) has-error @endif'>
        {{ Form::label('product_gst', 'product_gst',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_gst', '1') }}
            @if ($errors->has('product_gst')) <p class="help-block">{{ $errors->first('product_gst') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/order_products" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
