    <div class='form-group'>
        <div class=" col-sm-12">
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Return</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}

			<label class='btn btn-default pull-right'>
					Upload Photo<input type='file' name='file' style='display: none;' id='file' onchange='readURL(this);'>
			</label>
        </div>
    </div>
	<br>
	<div class='form-group  @if ($errors->has('patient_name')) has-error @endif'>
        <label for='patient_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('patient_name', null, ['class'=>'form-control input-lg','placeholder'=>'','maxlength'=>'50','style'=>'text-transform: uppercase']) }}
            @if ($errors->has('patient_name')) <p class="help-block">{{ $errors->first('patient_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('title_code')) has-error @endif'>
        {{ Form::label('Title', 'Title',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-4'>
            {{ Form::select('title_code', $title,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('title_code')) <p class="help-block">{{ $errors->first('title_code') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_birthdate')) has-error @endif'>
						{{ Form::label('Birthdate', 'Birthdate',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							<input id="patient_birthdate" name="patient_birthdate" type="text">
							@if ($errors->has('patient_birthdate')) <p class="help-block">{{ $errors->first('patient_birthdate') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_birthtime')) has-error @endif'>
						{{ Form::label('Birthtime', 'Birthtime',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							<input id="patient_birthtime" name="patient_birthtime" type="text">
							@if ($errors->has('patient_birthtime')) <p class="help-block">{{ $errors->first('patient_birthtime') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
						<label for='gender_code' class='col-sm-4 control-label'>Gender<span style='color:red;'> *</span></label>
						<div class='col-sm-8'>
							{{ Form::select('gender_code', $gender,null, ['class'=>'form-control','maxlength'=>'1']) }}
							@if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
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

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('religion_code')) has-error @endif'>
						{{ Form::label('Religion', 'Religion',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('religion_code', $religion,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('religion_code')) <p class="help-block">{{ $errors->first('religion_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('marital_code')) has-error @endif'>
						{{ Form::label('Marital Status', 'Marital Status',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('marital_code', $marital,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('marital_code')) <p class="help-block">{{ $errors->first('marital_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('nation_code')) has-error @endif'>
						{{ Form::label('Nationality', 'Nationality',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('nation_code', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('nation_code')) <p class="help-block">{{ $errors->first('nation_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('race_code')) has-error @endif'>
						{{ Form::label('Race', 'Race',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('race_code', $race,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('race_code')) <p class="help-block">{{ $errors->first('race_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('occupation_code')) has-error @endif'>
						{{ Form::label('Occupation', 'Occupation',['class'=>'col-sm-4 control-label']) }}
						<div class='col-sm-8'>
							{{ Form::select('occupation_code', $occupation,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('occupation_code')) <p class="help-block">{{ $errors->first('occupation_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('flag_code')) has-error @endif'>
						<label for='flag_code' class='col-sm-4 control-label'>Flag</label>
						<div class='col-sm-8'>
							{{ Form::select('flag_code', $flag,null, ['class'=>'form-control','maxlength'=>'1']) }}
							@if ($errors->has('flag_code')) <p class="help-block">{{ $errors->first('flag_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class='page-header'>
		<h4>Identification</h4>
	</div>
	<div class='form-group  @if ($errors->has('patient_is_unknown')) has-error @endif'>
		<label for='unkown' class='col-sm-2 control-label'></label>
        <div class='col-sm-10'>
            {{ Form::checkbox('patient_is_unknown', '1') }} <strong>Patient cannot be identified</strong>
            @if ($errors->has('patient_is_unknown')) <p class="help-block">{{ $errors->first('patient_is_unknown') }}</p> @endif
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


	<div class='page-header'>
		<h4>Contact Information</h4>
	</div>
	<div class="row">
			<div class="col-xs-6">
 <div class='form-group  @if ($errors->has('patient_phone_home')) has-error @endif'>
        {{ Form::label('Phone Home', 'Phone Home',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_phone_home', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_home')) <p class="help-block">{{ $errors->first('patient_phone_home') }}</p> @endif
        </div>
    </div>
			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_phone_mobile')) has-error @endif'>
        {{ Form::label('Phone Mobile', 'Phone Mobile',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_phone_mobile', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_mobile')) <p class="help-block">{{ $errors->first('patient_phone_mobile') }}</p> @endif
        </div>
    </div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_phone_office')) has-error @endif'>
						{{ Form::label('Phone Office', 'Phone Office',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_phone_office', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
							@if ($errors->has('patient_phone_office')) <p class="help-block">{{ $errors->first('patient_phone_office') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_phone_fax')) has-error @endif'>
						{{ Form::label('Phone Fax', 'Phone Fax',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_phone_fax', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
							@if ($errors->has('patient_phone_fax')) <p class="help-block">{{ $errors->first('patient_phone_fax') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('patient_email')) has-error @endif'>
        {{ Form::label('Email', 'Email',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('patient_email', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('patient_email')) <p class="help-block">{{ $errors->first('patient_email') }}</p> @endif
        </div>
    </div>

	<div class='page-header'>
		<h4>Current Address</h4>
	</div>
    <div class='form-group  @if ($errors->has('patient_cur_street_1')) has-error @endif'>
        {{ Form::label('Street 1', 'Street 1',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('patient_cur_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_cur_street_1')) <p class="help-block">{{ $errors->first('patient_cur_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_cur_street_2')) has-error @endif'>
        {{ Form::label('Street 2', 'Street 2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('patient_cur_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_cur_street_2')) <p class="help-block">{{ $errors->first('patient_cur_street_2') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
				<div class='form-group  @if ($errors->has('patient_cur_city')) has-error @endif'>
						{{ Form::label('City', 'City',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_cur_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
							@if ($errors->has('patient_cur_city')) <p class="help-block">{{ $errors->first('patient_cur_city') }}</p> @endif
						</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class='form-group  @if ($errors->has('patient_cur_postcode')) has-error @endif'>
						{{ Form::label('Postcode', 'Postcode',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_cur_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'5']) }}
							@if ($errors->has('patient_cur_postcode')) <p class="help-block">{{ $errors->first('patient_cur_postcode') }}</p> @endif
						</div>
				</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_cur_state')) has-error @endif'>
						{{ Form::label('State', 'State',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('patient_cur_state', $state, null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('patient_cur_state')) <p class="help-block">{{ $errors->first('patient_cur_state') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_cur_country')) has-error @endif'>
						{{ Form::label('Country', 'Country',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::select('patient_cur_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('patient_cur_country')) <p class="help-block">{{ $errors->first('patient_cur_country') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	
	<div class='page-header'>
		<h4>Permanent Address</h4>
	</div>
    <div class='form-group  @if ($errors->has('patient_per_street_1')) has-error @endif'>
        {{ Form::label('Street 1', 'Street 1',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('patient_per_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_per_street_1')) <p class="help-block">{{ $errors->first('patient_per_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_per_street_2')) has-error @endif'>
        {{ Form::label('Street 2', 'Street 2',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('patient_per_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_per_street_2')) <p class="help-block">{{ $errors->first('patient_per_street_2') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					 <div class='form-group  @if ($errors->has('patient_per_city')) has-error @endif'>
						{{ Form::label('City', 'City',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_per_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
							@if ($errors->has('patient_per_city')) <p class="help-block">{{ $errors->first('patient_per_city') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_per_postcode')) has-error @endif'>
						{{ Form::label('Postcode', 'Postcode',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_per_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'5']) }}
							@if ($errors->has('patient_per_postcode')) <p class="help-block">{{ $errors->first('patient_per_postcode') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
   
<div class="row">
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_per_state')) has-error @endif'>
        {{ Form::label('State', 'State',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::select('patient_per_state', $state, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('patient_per_state')) <p class="help-block">{{ $errors->first('patient_per_state') }}</p> @endif
        </div>
    </div>


			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_per_country')) has-error @endif'>
        {{ Form::label('Country', 'Country',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
			{{ Form::select('patient_per_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('patient_per_country')) <p class="help-block">{{ $errors->first('patient_per_country') }}</p> @endif
        </div>
    </div>


			</div>
	</div>

	<br>
    <div class='form-group'>
        <div class="col-sm-10">
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Return</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">

			</div>
			<div class="col-xs-6">

			</div>
	</div>

	<script>
		$(function(){
				$('#patient_birthdate').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $patient->patient_birthdate }}',
						maxYear: 2016,
						minYear: 1900,
						customClass: 'select'
				});    
		});

		$(function(){
				$('#patient_birthtime').combodate({
						format: "HH:mm",
						template: "HH : mm",
						value: '{{ $patient->patient_birthtime }}',
						minuteStep: 1,
						customClass: 'select'
				});    
		});

		function readURL(input) {
			if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function (e) {
						$('#show_image')
							.attr('src', e.target.result)
							.width(75)
							.heigt(90);
					};
					reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
