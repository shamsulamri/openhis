	<div class='form-group  @if ($errors->has('patient_is_unknown')) has-error @endif'>
		<label for='unkown' class='col-sm-2 control-label'></label>
        <div class='col-sm-10'>
            {{ Form::checkbox('patient_is_unknown', '1') }} <strong>Patient cannot be identified or unknown</strong>
            @if ($errors->has('patient_is_unknown')) <p class="help-block">{{ $errors->first('patient_is_unknown') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('title_code')) has-error @endif'>
        {{ Form::label('Title', 'Title',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('title_code', $title,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('title_code')) <p class="help-block">{{ $errors->first('title_code') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('patient_name')) has-error @endif'>
        <label for='patient_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('patient_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('patient_name')) <p class="help-block">{{ $errors->first('patient_name') }}</p> @endif
        </div>
    </div>
	
	<div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
        <label for='gender_code' class='col-sm-2 control-label'>Gender<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('gender_code', $gender,null, ['class'=>'form-control','maxlength'=>'1']) }}
            @if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
 <div class='form-group  @if ($errors->has('patient_birthdate')) has-error @endif'>
        {{ Form::label('Birthdate', 'Birthdate',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_birthdate', null, ['class'=>'form-control','placeholder'=>'dd/mm/yyyy',]) }}
            @if ($errors->has('patient_birthdate')) <p class="help-block">{{ $errors->first('patient_birthdate') }}</p> @endif
        </div>
    </div>
			</div>
			<div class="col-xs-6">
       <div class='form-group  @if ($errors->has('patient_birthtime')) has-error @endif'>
        {{ Form::label('Birthtime', 'Birthtime',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_birthtime', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_birthtime')) <p class="help-block">{{ $errors->first('patient_birthtime') }}</p> @endif
        </div>
    </div>


			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_new_ic')) has-error @endif'>
        {{ Form::label('New Identification', 'New Identification',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_new_ic', null, ['class'=>'form-control','placeholder'=>'MyKad number','maxlength'=>'20']) }}
            @if ($errors->has('patient_new_ic')) <p class="help-block">{{ $errors->first('patient_new_ic') }}</p> @endif
        </div>
    </div>


			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_old_ic')) has-error @endif'>
        {{ Form::label('Old Identification', 'Old Identification',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_old_ic', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_old_ic')) <p class="help-block">{{ $errors->first('patient_old_ic') }}</p> @endif
        </div>
    </div>


			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
 <div class='form-group  @if ($errors->has('patient_birth_certificate')) has-error @endif'>
        {{ Form::label('Birth Certificate', 'Birth Certificate',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_birth_certificate', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_birth_certificate')) <p class="help-block">{{ $errors->first('patient_birth_certificate') }}</p> @endif
        </div>
    </div>


			</div>
			<div class="col-xs-6">
       <div class='form-group  @if ($errors->has('patient_passport')) has-error @endif'>
        {{ Form::label('Passport', 'Passport',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_passport', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_passport')) <p class="help-block">{{ $errors->first('patient_passport') }}</p> @endif
        </div>
    </div>


			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_military_id')) has-error @endif'>
        {{ Form::label('Military ID', 'Military ID',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_military_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_military_id')) <p class="help-block">{{ $errors->first('patient_military_id') }}</p> @endif
        </div>
    </div>


			</div>
			<div class="col-xs-6">
	<div class='form-group  @if ($errors->has('patient_police_id')) has-error @endif'>
        {{ Form::label('Police ID', 'Police ID',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_police_id', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_police_id')) <p class="help-block">{{ $errors->first('patient_police_id') }}</p> @endif
        </div>
    </div>


			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
<div class='form-group  @if ($errors->has('patient_splp')) has-error @endif'>
	{{ Form::label('SPLP', 'SPLP',['class'=>'col-md-4 control-label']) }}
	<div class='col-md-8'>
		{{ Form::text('patient_splp', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
		@if ($errors->has('patient_splp')) <p class="help-block">{{ $errors->first('patient_splp') }}</p> @endif
	</div>
</div>


			</div>
			<div class="col-xs-6">
<div class='form-group  @if ($errors->has('patient_age')) has-error @endif'>
	{{ Form::label('Estimated Age', 'Estimated Age',['class'=>'col-md-4 control-label']) }}
	<div class='col-md-8'>
		{{ Form::text('patient_age', null, ['class'=>'form-control','placeholder'=>'Fill for unknown patient','maxlength'=>'20']) }}
		@if ($errors->has('patient_age')) <p class="help-block">{{ $errors->first('patient_age') }}</p> @endif
	</div>
</div>


			</div>
	</div>


    <div class='form-group  @if ($errors->has('religion_code')) has-error @endif'>
        {{ Form::label('Religion', 'Religion',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('religion_code', $religion,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('religion_code')) <p class="help-block">{{ $errors->first('religion_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('marital_code')) has-error @endif'>
        {{ Form::label('Marital Status', 'Marital Status',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('marital_code', $marital,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('marital_code')) <p class="help-block">{{ $errors->first('marital_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('nation_code')) has-error @endif'>
        {{ Form::label('Nationality', 'Nationality',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('nation_code', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('nation_code')) <p class="help-block">{{ $errors->first('nation_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('race_code')) has-error @endif'>
        {{ Form::label('Race', 'Race',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('race_code', $race,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('race_code')) <p class="help-block">{{ $errors->first('race_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('occupation_code')) has-error @endif'>
        {{ Form::label('Occupation', 'Occupation',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('occupation_code', $occupation,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('occupation_code')) <p class="help-block">{{ $errors->first('occupation_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('registration_code')) has-error @endif'>
        {{ Form::label('Registration Type', 'Registration Type',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('registration_code', $registration,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('registration_code')) <p class="help-block">{{ $errors->first('registration_code') }}</p> @endif
        </div>
    </div>
	
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">

			</div>
			<div class="col-xs-6">

			</div>
	</div>

