
    <div class='form-group  @if ($errors->has('encounter_name')) has-error @endif'>
        <label for='encounter_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('encounter_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('encounter_name')) <p class="help-block">{{ $errors->first('encounter_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('encounter_bill_prefix')) has-error @endif'>
        <label for='encounter_bill_prefix' class='col-sm-3 control-label'>Prefix<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('encounter_bill_prefix', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('encounter_bill_prefix')) <p class="help-block">{{ $errors->first('encounter_bill_prefix') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('encounter_active')) has-error @endif'>
        <label for='encounter_active' class='col-sm-3 control-label'>Active</label>
        <div class='col-sm-9'>
			{{ Form::checkbox('encounter_active', '1') }} 
			@if ($errors->has('encounter_active')) <p class="help-block">{{ $errors->first('encounter_active') }}</p> @endif
		</div>
	</div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/encounter_types" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
