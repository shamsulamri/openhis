
    <div class='form-group  @if ($errors->has('admission_code')) has-error @endif'>
        {{ Form::label('admission_code', 'Admission Type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('admission_code', $admission_type,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('admission_code')) <p class="help-block">{{ $errors->first('admission_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('referral_code')) has-error @endif'>
        {{ Form::label('referral_code', 'Referral',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('referral_code', $referral, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('referral_code')) <p class="help-block">{{ $errors->first('referral_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bed_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-2 control-label'>Bed<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('bed_code', $bed,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('bed_code')) <p class="help-block">{{ $errors->first('bed_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-2 control-label'>Consultant<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('user_id', $consultant,null, ['class'=>'form-control']) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
			
	{{ Form::hidden('encounter_id', null) }}
