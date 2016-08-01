
    <div class='form-group  @if ($errors->has('patient_name')) has-error @endif'>
        <label for='patient_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('patient_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('patient_name')) <p class="help-block">{{ $errors->first('patient_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
        <label for='gender_code' class='col-sm-3 control-label'>Gender<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('gender_code', $gender,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_new_ic')) has-error @endif'>
        {{ Form::label('patient_new_ic', 'New IC',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_new_ic', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_new_ic')) <p class="help-block">{{ $errors->first('patient_new_ic') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('relation_code')) has-error @endif'>
        {{ Form::label('relation_code', 'Relationship',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('relation_code', $relation,$relation_code, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('relation_code')) <p class="help-block">{{ $errors->first('relation_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_phone_home')) has-error @endif'>
        {{ Form::label('patient_phone_home', 'Phone Home',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_phone_home', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_home')) <p class="help-block">{{ $errors->first('patient_phone_home') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_phone_mobile')) has-error @endif'>
        {{ Form::label('patient_phone_mobile', 'Phone Mobile',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_phone_mobile', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_mobile')) <p class="help-block">{{ $errors->first('patient_phone_mobile') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_phone_office')) has-error @endif'>
        {{ Form::label('patient_phone_office', 'Phone Office',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_phone_office', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_office')) <p class="help-block">{{ $errors->first('patient_phone_office') }}</p> @endif
        </div>
    </div>

	{{ Form::hidden('patient_id', $patient_id) }}

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/dependants?patient_id={{ $patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
