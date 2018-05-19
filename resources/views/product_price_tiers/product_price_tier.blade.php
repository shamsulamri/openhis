
    <div class='form-group  @if ($errors->has('charge_code')) has-error @endif'>
        <label for='charge_code' class='col-sm-2 control-label'>Tier<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('charge_code', $charge,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('charge_code')) <p class="help-block">{{ $errors->first('charge_code') }}</p> @endif
        </div>
    </div>

	<hr>
	<h3>Cost Range</h3>
    <div class='form-group  @if ($errors->has('tier_min')) has-error @endif'>
        {{ Form::label('tier_min', 'Min',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('tier_min', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('tier_min')) <p class="help-block">{{ $errors->first('tier_min') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tier_max')) has-error @endif'>
        <label for='tier_max' class='col-sm-2 control-label'>Max</label>
        <div class='col-sm-10'>
            {{ Form::text('tier_max', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('tier_max')) <p class="help-block">{{ $errors->first('tier_max') }}</p> @endif
        </div>
    </div>

	
	<hr>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('tier_outpatient')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
								<h3>Public</h3>
						</div>
					</div>
					<div class='form-group  @if ($errors->has('tier_outpatient')) has-error @endif'>
						{{ Form::label('tier_outpatient', 'Price',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('tier_outpatient', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tier_outpatient')) <p class="help-block">{{ $errors->first('tier_outpatient') }}</p> @endif
							<br>or
						</div>
					</div>
			
					<div class='form-group  @if ($errors->has('tier_outpatient_multiplier')) has-error @endif'>
						{{ Form::label('tier_outpatient_multiplier', 'Multiplier',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-3'>
							{{ Form::text('tier_outpatient_multiplier', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tier_outpatient_multiplier')) <p class="help-block">{{ $errors->first('tier_outpatient_multiplier') }}</p> @endif
						</div>
						{{ Form::label('tier_outpatient_limit', 'Cap',['class'=>'col-sm-2 control-label']) }}
						<div class='col-sm-3'>
							{{ Form::text('tier_outpatient_limit', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tier_outpatient_limit')) <p class="help-block">{{ $errors->first('tier_outpatient_limit') }}</p> @endif
						</div>
					</div>
			</div>

			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('tier_outpatient')) has-error @endif'>
						<div class='col-sm-offset-4 col-sm-8'>
									<h3>Sponsor</h3>
						</div>
					</div>

					<div class='form-group  @if ($errors->has('tier_inpatient')) has-error @endif'>
						{{ Form::label('tier_inpatient', 'Price',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::text('tier_inpatient', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tier_inpatient')) <p class="help-block">{{ $errors->first('tier_inpatient') }}</p> @endif
							<br>or
						</div>
					</div>


					<div class='form-group  @if ($errors->has('tier_inpatient_multiplier')) has-error @endif'>
						{{ Form::label('tier_inpatient_multiplier', 'Multiplier',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-3'>
							{{ Form::text('tier_inpatient_multiplier', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tier_inpatient_multiplier')) <p class="help-block">{{ $errors->first('tier_inpatient_multiplier') }}</p> @endif
						</div>
						{{ Form::label('tier_inpatient_limit', 'Cap',['class'=>'col-sm-2 control-label']) }}
						<div class='col-sm-3'>
							{{ Form::text('tier_inpatient_limit', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tier_inpatient_limit')) <p class="help-block">{{ $errors->first('tier_inpatient_limit') }}</p> @endif
						</div>
					</div>

			</div>
	</div>

	<br>
	<div class='form-group'>
		<div class="col-sm-offset-2 col-sm-10">
			<a class="btn btn-default" href="/product_price_tiers" role="button">Cancel</a>
			{{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
		</div>
	</div>

