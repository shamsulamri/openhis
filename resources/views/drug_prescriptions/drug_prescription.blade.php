
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_strength')) has-error @endif'>
						{{ Form::label('drug_strength', 'Strength',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('drug_strength', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('drug_strength')) <p class="help-block">{{ $errors->first('drug_strength') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						<div class='col-md-12'>
							{{ Form::select('unit_code', $unit,null, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_dosage')) has-error @endif'>
						{{ Form::label('drug_dosage', 'Dosage',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('drug_dosage', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('drug_dosage')) <p class="help-block">{{ $errors->first('drug_dosage') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('dosage_code')) has-error @endif'>
						<div class='col-md-12'>
							{{ Form::select('dosage_code', $dosage,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('dosage_code')) <p class="help-block">{{ $errors->first('dosage_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('route_code')) has-error @endif'>
        {{ Form::label('route_code', 'Route',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('route_code', $route,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('route_code')) <p class="help-block">{{ $errors->first('route_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
        {{ Form::label('frequency_code', 'Frequency',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('frequency_code', $frequency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('drug_duration')) has-error @endif'>
						{{ Form::label('drug_duration', 'Period',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('drug_duration', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('drug_duration')) <p class="help-block">{{ $errors->first('drug_duration') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						<div class='col-md-12'>
							{{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('drug_total_unit')) has-error @endif'>
        {{ Form::label('drug_total_unit', 'Total Unit',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('drug_total_unit', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('drug_total_unit')) <p class="help-block">{{ $errors->first('drug_total_unit') }}</p> @endif
        </div>
    </div>


    <div class='form-group  @if ($errors->has('drug_instruction')) has-error @endif'>
        {{ Form::label('drug_instruction', 'Instruction',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('drug_instruction', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('drug_instruction')) <p class="help-block">{{ $errors->first('drug_instruction') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('drug_prn')) has-error @endif'>
        {{ Form::label('drug_prn', 'PRN',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('drug_prn', '1') }}
            @if ($errors->has('drug_prn')) <p class="help-block">{{ $errors->first('drug_prn') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('drug_meal')) has-error @endif'>
        {{ Form::label('drug_meal', 'After Meal',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('drug_meal', '1') }}
            @if ($errors->has('drug_meal')) <p class="help-block">{{ $errors->first('drug_meal') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="javascript:window.history.back()" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('drug_code', null) }}
