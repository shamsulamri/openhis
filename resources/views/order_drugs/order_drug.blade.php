
    <div class='form-group  @if ($errors->has('consultation_id')) has-error @endif'>
        <label for='consultation_id' class='col-sm-2 control-label'>consultation_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('consultation_id', $consultation_id, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('consultation_id')) <p class="help-block">{{ $errors->first('consultation_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('product_code', $product_code, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_strength')) has-error @endif'>
        {{ Form::label('drug_strength', 'drug_strength',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_strength', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_strength')) <p class="help-block">{{ $errors->first('drug_strength') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
        {{ Form::label('unit_code', 'unit_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('unit_code', $unit,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_dosage')) has-error @endif'>
        {{ Form::label('drug_dosage', 'drug_dosage',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_dosage', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_dosage')) <p class="help-block">{{ $errors->first('drug_dosage') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dosage_code')) has-error @endif'>
        {{ Form::label('dosage_code', 'dosage_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('dosage_code', $dosage,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('dosage_code')) <p class="help-block">{{ $errors->first('dosage_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('route_code')) has-error @endif'>
        {{ Form::label('route_code', 'route_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('route_code', $route,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('route_code')) <p class="help-block">{{ $errors->first('route_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
        {{ Form::label('frequency_code', 'frequency_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('frequency_code', $frequency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_period')) has-error @endif'>
        {{ Form::label('drug_period', 'drug_period',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_period', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_period')) <p class="help-block">{{ $errors->first('drug_period') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
        {{ Form::label('period_code', 'period_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_total_unit')) has-error @endif'>
        {{ Form::label('drug_total_unit', 'drug_total_unit',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_total_unit', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_total_unit')) <p class="help-block">{{ $errors->first('drug_total_unit') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_prn')) has-error @endif'>
        {{ Form::label('drug_prn', 'drug_prn',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('drug_prn', '1') }}
            @if ($errors->has('drug_prn')) <p class="help-block">{{ $errors->first('drug_prn') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_after_meal')) has-error @endif'>
        {{ Form::label('drug_after_meal', 'drug_after_meal',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('drug_after_meal', '1') }}
            @if ($errors->has('drug_after_meal')) <p class="help-block">{{ $errors->first('drug_after_meal') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
        {{ Form::label('order_is_discharge', 'order_is_discharge',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_is_discharge', '1') }}
            @if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/order_drugs" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
