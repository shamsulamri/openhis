
    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
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

    <div class='form-group  @if ($errors->has('product_unit_charge')) has-error @endif'>
        {{ Form::label('product_unit_charge', 'product_unit_charge',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('product_unit_charge', '1') }}
            @if ($errors->has('product_unit_charge')) <p class="help-block">{{ $errors->first('product_unit_charge') }}</p> @endif
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

    <div class='form-group  @if ($errors->has('product_conversion_unit')) has-error @endif'>
        {{ Form::label('product_conversion_unit', 'product_conversion_unit',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('product_conversion_unit', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_conversion_unit')) <p class="help-block">{{ $errors->first('product_conversion_unit') }}</p> @endif
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
            {{ Form::text('product_gst', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_gst')) <p class="help-block">{{ $errors->first('product_gst') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('status_code')) has-error @endif'>
        {{ Form::label('status_code', 'status_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('status_code', $status,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/product_searches" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
