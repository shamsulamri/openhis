
    <div class='form-group  @if ($errors->has('order_custom_id')) has-error @endif'>
        {{ Form::label('order_custom_id', 'order_custom_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_custom_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('order_custom_id')) <p class="help-block">{{ $errors->first('order_custom_id') }}</p> @endif
        </div>
    </div>

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

    <div class='form-group  @if ($errors->has('admission_id')) has-error @endif'>
        <label for='admission_id' class='col-sm-2 control-label'>admission_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('admission_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('admission_id')) <p class="help-block">{{ $errors->first('admission_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        {{ Form::label('ward_code', 'ward_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('location_code')) has-error @endif'>
        {{ Form::label('location_code', 'location_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('location_code', $location,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('store_code', 'store_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('product_code', $product,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bom_code')) has-error @endif'>
        {{ Form::label('bom_code', 'bom_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('bom_code', $bom,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('bom_code')) <p class="help-block">{{ $errors->first('bom_code') }}</p> @endif
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

	<!--
    <div class='form-group  @if ($errors->has('order_multiple')) has-error @endif'>
        <label for='order_multiple' class='col-sm-2 control-label'>order_multiple<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::checkbox('order_multiple', '1') }}
            @if ($errors->has('order_multiple')) <p class="help-block">{{ $errors->first('order_multiple') }}</p> @endif
        </div>
    </div>
	-->

    <div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
        <label for='order_quantity_request' class='col-sm-2 control-label'>order_quantity_request<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_quantity_request', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_request')) <p class="help-block">{{ $errors->first('order_quantity_request') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_quantity_supply')) has-error @endif'>
        {{ Form::label('order_quantity_supply', 'order_quantity_supply',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_quantity_supply', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_supply')) <p class="help-block">{{ $errors->first('order_quantity_supply') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_unit_price')) has-error @endif'>
        {{ Form::label('order_unit_price', 'order_unit_price',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_unit_price', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_unit_price')) <p class="help-block">{{ $errors->first('order_unit_price') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
        <label for='unit_code' class='col-sm-2 control-label'>unit_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('unit_code', $unit,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_discount')) has-error @endif'>
        {{ Form::label('order_discount', 'order_discount',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_discount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_discount')) <p class="help-block">{{ $errors->first('order_discount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_markup')) has-error @endif'>
        {{ Form::label('order_markup', 'order_markup',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_markup', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_markup')) <p class="help-block">{{ $errors->first('order_markup') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
        {{ Form::label('order_is_discharge', 'order_is_discharge',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_is_discharge', '1') }}
            @if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_future')) has-error @endif'>
        {{ Form::label('order_is_future', 'order_is_future',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_is_future', '1') }}
            @if ($errors->has('order_is_future')) <p class="help-block">{{ $errors->first('order_is_future') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_include_stat')) has-error @endif'>
        {{ Form::label('order_include_stat', 'order_include_stat',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_include_stat', '1') }}
            @if ($errors->has('order_include_stat')) <p class="help-block">{{ $errors->first('order_include_stat') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_report')) has-error @endif'>
        {{ Form::label('order_report', 'order_report',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_report', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('order_report')) <p class="help-block">{{ $errors->first('order_report') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('reported_by')) has-error @endif'>
        {{ Form::label('reported_by', 'reported_by',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('reported_by', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('reported_by')) <p class="help-block">{{ $errors->first('reported_by') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_diagnostic_report')) has-error @endif'>
        {{ Form::label('order_diagnostic_report', 'order_diagnostic_report',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('order_diagnostic_report', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'4294967295']) }}
            @if ($errors->has('order_diagnostic_report')) <p class="help-block">{{ $errors->first('order_diagnostic_report') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('updated_by')) has-error @endif'>
        {{ Form::label('updated_by', 'updated_by',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('updated_by', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('updated_by')) <p class="help-block">{{ $errors->first('updated_by') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('completed_by')) has-error @endif'>
        {{ Form::label('completed_by', 'completed_by',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('completed_by', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('completed_by')) <p class="help-block">{{ $errors->first('completed_by') }}</p> @endif
        </div>
    </div>

	<!--
    <div class='form-group  @if ($errors->has('completed_at')) has-error @endif'>
        {{ Form::label('completed_at', 'completed_at',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('completed_at', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('completed_at')) <p class="help-block">{{ $errors->first('completed_at') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dispensed_at')) has-error @endif'>
        {{ Form::label('dispensed_at', 'dispensed_at',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('dispensed_at', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('dispensed_at')) <p class="help-block">{{ $errors->first('dispensed_at') }}</p> @endif
        </div>
    </div>
	-->

    <div class='form-group  @if ($errors->has('dispensed_by')) has-error @endif'>
        {{ Form::label('dispensed_by', 'dispensed_by',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('dispensed_by', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('dispensed_by')) <p class="help-block">{{ $errors->first('dispensed_by') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('post_id')) has-error @endif'>
        <label for='post_id' class='col-sm-2 control-label'>post_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('post_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('post_id')) <p class="help-block">{{ $errors->first('post_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('origin_id')) has-error @endif'>
        {{ Form::label('origin_id', 'origin_id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('origin_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('origin_id')) <p class="help-block">{{ $errors->first('origin_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/orders" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
