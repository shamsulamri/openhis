
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-2 control-label'>encounter_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('encounter_id')) <p class="help-block">{{ $errors->first('encounter_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('consultation_id')) has-error @endif'>
        <label for='consultation_id' class='col-sm-2 control-label'>consultation_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('consultation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('consultation_id')) <p class="help-block">{{ $errors->first('consultation_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-2 control-label'>user_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
        <label for='order_quantity_request' class='col-sm-2 control-label'>order_quantity_request<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_quantity_request', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_request')) <p class="help-block">{{ $errors->first('order_quantity_request') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_description')) has-error @endif'>
        {{ Form::label('order_description', 'order_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('order_description')) <p class="help-block">{{ $errors->first('order_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_completed')) has-error @endif'>
        {{ Form::label('order_completed', 'order_completed',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_completed', '1') }}
            @if ($errors->has('order_completed')) <p class="help-block">{{ $errors->first('order_completed') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_quantity_supply')) has-error @endif'>
        {{ Form::label('order_quantity_supply', 'order_quantity_supply',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_quantity_supply', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_supply')) <p class="help-block">{{ $errors->first('order_quantity_supply') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        {{ Form::label('location_code', 'location_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('location_code', $location,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_sale_price')) has-error @endif'>
        {{ Form::label('order_sale_price', 'order_sale_price',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_sale_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_sale_price')) <p class="help-block">{{ $errors->first('order_sale_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_discount')) has-error @endif'>
        {{ Form::label('order_discount', 'order_discount',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_discount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_discount')) <p class="help-block">{{ $errors->first('order_discount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
        {{ Form::label('order_is_discharge', 'order_is_discharge',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_is_discharge', '1') }}
            @if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_report')) has-error @endif'>
        {{ Form::label('order_report', 'order_report',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_report', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('order_report')) <p class="help-block">{{ $errors->first('order_report') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('post_id')) has-error @endif'>
        <label for='post_id' class='col-sm-2 control-label'>post_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('post_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('post_id')) <p class="help-block">{{ $errors->first('post_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/admission_tasks" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
