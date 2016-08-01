
    <div class='form-group  @if ($errors->has('category_code')) has-error @endif'>
        {{ Form::label('category_code', 'category_code',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('category_code', $category,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_trade_name')) has-error @endif'>
        {{ Form::label('drug_trade_name', 'drug_trade_name',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('drug_trade_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('drug_trade_name')) <p class="help-block">{{ $errors->first('drug_trade_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_generic_name')) has-error @endif'>
        {{ Form::label('drug_generic_name', 'drug_generic_name',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('drug_generic_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('drug_generic_name')) <p class="help-block">{{ $errors->first('drug_generic_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_registration_number')) has-error @endif'>
        {{ Form::label('drug_registration_number', 'drug_registration_number',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('drug_registration_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('drug_registration_number')) <p class="help-block">{{ $errors->first('drug_registration_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_unit_charge')) has-error @endif'>
        {{ Form::label('drug_unit_charge', 'drug_unit_charge',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('drug_unit_charge', '1') }}
            @if ($errors->has('drug_unit_charge')) <p class="help-block">{{ $errors->first('drug_unit_charge') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/drugs" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
