
<div class='page-header'>
<h3>Admission</h3>
</div>
    <div class='form-group  @if ($errors->has('admission_code')) has-error @endif'>
        {{ Form::label('admission_code', 'Admission Type',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('admission_code', $admission_type,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('admission_code')) <p class="help-block">{{ $errors->first('admission_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('referral_code')) has-error @endif'>
        {{ Form::label('referral_code', 'Referral',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('referral_code', $referral, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('referral_code')) <p class="help-block">{{ $errors->first('referral_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-3 control-label'>Consultant<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('user_id', $consultant,null, ['class'=>'form-control']) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
			<small>Not required for death cases</small>
        </div>
    </div>

			
	{{ Form::hidden('encounter_id', null) }}

@if ($encounter->encounter_code=='daycare')
<script>
	document.getElementById('admission_code').disabled = true;
	document.getElementById('referral_code').disabled = true;
</script>
@endif
