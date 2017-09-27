
    <div class='form-group  @if ($errors->has('drug_dosage')) has-error @endif'>
        {{ Form::label('drug_dosage', 'Dosage',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_dosage', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_dosage')) <p class="help-block">{{ $errors->first('drug_dosage') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('dosage_code')) has-error @endif'>
        {{ Form::label('dosage_code', 'Dosage Unit',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('dosage_code', $dosage,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('dosage_code')) <p class="help-block">{{ $errors->first('dosage_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('route_code')) has-error @endif'>
        {{ Form::label('route_code', 'Route',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('route_code', $route,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('route_code')) <p class="help-block">{{ $errors->first('route_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
        {{ Form::label('frequency_code', 'Frequency',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('frequency_code', $frequency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_duration')) has-error @endif'>
        {{ Form::label('drug_duration', 'Duration',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_duration', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_duration')) <p class="help-block">{{ $errors->first('drug_duration') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
        {{ Form::label('period_code', 'Period',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_total_unit')) has-error @endif'>
        {{ Form::label('drug_total_unit', 'Total Unit',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('drug_total_unit', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_total_unit')) <p class="help-block">{{ $errors->first('drug_total_unit') }}</p> @endif
        </div>
    </div>

	<!--
    <div class='form-group  @if ($errors->has('instruction_code')) has-error @endif'>
        {{ Form::label('instruction_code', 'Instruction',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('instruction_code', $instruction,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('instruction_code')) <p class="help-block">{{ $errors->first('instruction_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('special_code')) has-error @endif'>
        {{ Form::label('special_code', 'Special Instruction',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('special_code', $special,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('special_code')) <p class="help-block">{{ $errors->first('special_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('caution_code')) has-error @endif'>
        {{ Form::label('caution_code', 'Caution',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('caution_code', $caution,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('caution_code')) <p class="help-block">{{ $errors->first('caution_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('indication_code')) has-error @endif'>
        {{ Form::label('indication_code', 'Indication',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('indication_code', $indication,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('indication_code')) <p class="help-block">{{ $errors->first('indication_code') }}</p> @endif
        </div>
    </div>
	-->

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/drug_prescriptions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
