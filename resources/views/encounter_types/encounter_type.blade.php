
    <div class='form-group  @if ($errors->has('encounter_name')) has-error @endif'>
        <label for='encounter_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('encounter_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('encounter_name')) <p class="help-block">{{ $errors->first('encounter_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('profit_multiplier')) has-error @endif'>
        <label for='profit_multiplier' class='col-sm-3 control-label'>Profit Multiplier</label>
        <div class='col-sm-9'>
				<div name="profit_multiplier" class="input-group profit_multiplier" data-autoclose="true">
						{{ Form::text('profit_multiplier', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
						@if ($errors->has('profit_multiplier')) <p class="help-block">{{ $errors->first('profit_multiplier') }}</p> @endif
						<span class="input-group-addon">
							<span class="fa fa-percent"></span>
						</span>
				</div>
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/encounter_types" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
