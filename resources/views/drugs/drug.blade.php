
    <div class='form-group  @if ($errors->has('drug_generic_name')) has-error @endif'>
        {{ Form::label('drug_generic_name', 'drug_generic_name',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_generic_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('drug_generic_name')) <p class="help-block">{{ $errors->first('drug_generic_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_label')) has-error @endif'>
        {{ Form::label('drug_label', 'drug_label',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_label', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('drug_label')) <p class="help-block">{{ $errors->first('drug_label') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('active_ingredient')) has-error @endif'>
        {{ Form::label('active_ingredient', 'active_ingredient',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('active_ingredient', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('active_ingredient')) <p class="help-block">{{ $errors->first('active_ingredient') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_category')) has-error @endif'>
        {{ Form::label('drug_category', 'drug_category',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_category', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('drug_category')) <p class="help-block">{{ $errors->first('drug_category') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_schedule')) has-error @endif'>
        {{ Form::label('drug_schedule', 'drug_schedule',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_schedule', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('drug_schedule')) <p class="help-block">{{ $errors->first('drug_schedule') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_formulary')) has-error @endif'>
        {{ Form::label('drug_formulary', 'drug_formulary',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_formulary', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('drug_formulary')) <p class="help-block">{{ $errors->first('drug_formulary') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_type')) has-error @endif'>
        {{ Form::label('drug_type', 'drug_type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_type', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('drug_type')) <p class="help-block">{{ $errors->first('drug_type') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('item_subgroup')) has-error @endif'>
        {{ Form::label('item_subgroup', 'item_subgroup',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('item_subgroup', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('item_subgroup')) <p class="help-block">{{ $errors->first('item_subgroup') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('trade_name')) has-error @endif'>
        {{ Form::label('trade_name', 'trade_name',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('trade_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('trade_name')) <p class="help-block">{{ $errors->first('trade_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('strength')) has-error @endif'>
        {{ Form::label('strength', 'strength',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('strength', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('strength')) <p class="help-block">{{ $errors->first('strength') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('uom_strength')) has-error @endif'>
        {{ Form::label('uom_strength', 'uom_strength',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('uom_strength', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('uom_strength')) <p class="help-block">{{ $errors->first('uom_strength') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dosage_form')) has-error @endif'>
        {{ Form::label('dosage_form', 'dosage_form',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('dosage_form', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('dosage_form')) <p class="help-block">{{ $errors->first('dosage_form') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('sku_uom')) has-error @endif'>
        {{ Form::label('sku_uom', 'sku_uom',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('sku_uom', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'250']) }}
            @if ($errors->has('sku_uom')) <p class="help-block">{{ $errors->first('sku_uom') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('special_code')) has-error @endif'>
        {{ Form::label('special_code', 'special_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('special_code', $special,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('special_code')) <p class="help-block">{{ $errors->first('special_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('instruction_code')) has-error @endif'>
        {{ Form::label('instruction_code', 'instruction_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('instruction_code', $instruction,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('instruction_code')) <p class="help-block">{{ $errors->first('instruction_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('caution_code')) has-error @endif'>
        {{ Form::label('caution_code', 'caution_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('caution_code', $caution,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('caution_code')) <p class="help-block">{{ $errors->first('caution_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/drugs" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
