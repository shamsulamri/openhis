    <div class='form-group'>
        <div class=" col-sm-12">
            	{{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
<input type="button" id="getInfo" value="Read MyKad" class='btn btn-primary'/>
        </div>
    </div>

<div class="mykad_reading">
	<div class='alert alert-warning' role='alert'><strong>Reading MyKad.</strong> Please wait until the process complete.</div>
</div>

<div class="mykad_complete">
	<div class='alert alert-info' role='alert'><strong>MyKad read successfully.</strong> Click on the Photo tab to upload image.</div>
</div>

<div class="mykad_error">
	<div class='alert alert-danger' role='alert'><strong>Error reading MyKad.</strong> Please check if your device is connected properly.</div>
</div>
<div class="tabs-container">
		<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab-1">Demography</a></li>
				<li class=""><a data-toggle="tab" href="#tab-3">Contact Information</a></li>
				<li class=""><a data-toggle="tab" href="#tab-4">Work Information</a></li>
				<li class=""><a data-toggle="tab" href="#tab-5">Photo</a></li>
		</ul>
		<div class="tab-content">
			<div id="tab-1" class="tab-pane active">
				<div class="panel-body">
					<div class='form-group  @if ($errors->has('patient_name')) has-error @endif'>
						<label for='patient_name' class='col-sm-2 control-label'>Name<span style='color:red;'> *</span></label>
						<div class='col-sm-10'>
							{{ Form::text('patient_name', null, ['id'=>'patient_name','class'=>'form-control','placeholder'=>'','maxlength'=>'50','style'=>'text-transform: uppercase']) }}
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
											<div class="input-group date">
												<input data-mask="99/99/9999" name="patient_birthdate" id="patient_birthdate" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($patient->patient_birthdate) }}">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
										<!--
										<div class='col-md-8'>
											<input id="patient_birthdate" name="patient_birthdate" type="text">
											@if ($errors->has('patient_birthdate')) <p class="help-block">{{ $errors->first('patient_birthdate') }}</p> @endif
										</div>
										-->
									</div>
							</div>
							<div class="col-xs-6">
									<div class='form-group  @if ($errors->has('patient_birthtime')) has-error @endif'>
										{{ Form::label('Birthtime', 'Birthtime',['class'=>'col-md-4 control-label']) }}
										<div class='col-md-8'>
											<!--
											<input id="patient_birthtime" name="patient_birthtime" type="text">
											@if ($errors->has('patient_birthtime')) <p class="help-block">{{ $errors->first('patient_birthtime') }}</p> @endif
											-->
												<div id="patient_birthtime" name="patient_birthtime" class="input-group clockpicker" data-autoclose="true">
														{{ Form::text('patient_birthtime', null, ['class'=>'form-control','data-mask'=>'99:99']) }}
														<span class="input-group-addon">
															<span class="fa fa-clock-o"></span>
														</span>
												</div>

										</div>
									</div>
							</div>
					</div>

					<div class="row">
							<div class="col-xs-6">
									<div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
										<label for='gender_code' class='col-sm-4 control-label'>Gender<span style='color:red;'> *</span></label>
										<div class='col-sm-8'>
											{{ Form::select('gender_code', $gender,null, ['id'=>'gender_code','class'=>'form-control','maxlength'=>'1']) }}
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
					<br>
					<div class='form-group  @if ($errors->has('patient_is_unknown')) has-error @endif'>
						<div class='col-sm-12'>
							<h4>Identification</h4>
							<hr>
							<h4>
							{{ Form::checkbox('patient_is_unknown', '1',['class'=>'checkbox']) }} No personal identification can be obtained
							</h4>
							<br>
						</div>
					</div>

					<div class="row">
							<div class="col-xs-6">
									<div class='form-group  @if ($errors->has('patient_new_ic')) has-error @endif'>
										{{ Form::label('New Identification', 'Identification',['class'=>'col-md-4 control-label']) }}
										<div class='col-md-8'>
											{{ Form::text('patient_new_ic', null, ['id'=>'patient_new_ic','class'=>'form-control','data-mask'=>'999999999999', 'placeholder'=>'MyKad number','maxlength'=>'20']) }}
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
				</div>
			</div>
			<div id="tab-2" class="tab-pane">
				<div class="panel-body">
				</div>
			</div>
			<!-- Work information -->
			<div id="tab-4" class="tab-pane">
				<div class="panel-body">
					<div class='row'>
								<div class="col-xs-6">
									<h4>Company Detail</h4>
									<hr>
									<div class='form-group  @if ($errors->has('patient_work_company')) has-error @endif'>
										{{ Form::label('Company Name', 'Company Name',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_work_company', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_work_company')) <p class="help-block">{{ $errors->first('patient_work_company') }}</p> @endif
										</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_work_rn')) has-error @endif'>
										{{ Form::label('Registration Number', 'Registration Number',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_work_rn', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_work_rn')) <p class="help-block">{{ $errors->first('patient_work_rn') }}</p> @endif
										</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_work_number')) has-error @endif'>
										{{ Form::label('Contact Number', 'Contact Number',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_work_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_work_number')) <p class="help-block">{{ $errors->first('patient_work_number') }}</p> @endif
										</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_work_person')) has-error @endif'>
										{{ Form::label('Contact Person', 'Contact Person',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_work_person', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_work_person')) <p class="help-block">{{ $errors->first('patient_work_person') }}</p> @endif
										</div>
									</div>

								</div>
								<div class="col-xs-6">
									<h4>Company Address</h4>
									<hr>
									<div class='form-group  @if ($errors->has('patient_work_street_1')) has-error @endif'>
										{{ Form::label('Street 1', 'Address 1',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_work_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_work_street_1')) <p class="help-block">{{ $errors->first('patient_work_street_1') }}</p> @endif
										</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_work_street_2')) has-error @endif'>
										{{ Form::label('Street 2', 'Address 2',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_work_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_work_street_2')) <p class="help-block">{{ $errors->first('patient_work_street_2') }}</p> @endif
										</div>
									</div>

									<div class="row">
											<div class="col-xs-6">
												<div class='form-group  @if ($errors->has('patient_work_postcode')) has-error @endif'>
														{{ Form::label('Postcode', 'Postcode',['class'=>'col-md-6 control-label']) }}
														<div class='col-md-6'>
															{{ Form::text('patient_work_postcode', null, ['class'=>'form-control','data-mask'=>'99999','placeholder'=>'','maxlength'=>'5','onblur'=>'work_postcode_change()','id'=>'patient_work_postcode']) }}
															@if ($errors->has('patient_work_postcode')) <p class="help-block">{{ $errors->first('patient_work_postcode') }}</p> @endif
														</div>
												</div>
											</div>
											<div class="col-xs-6">
												<div class='form-group  @if ($errors->has('patient_work_city')) has-error @endif'>
														{{ Form::label('City', 'City',['class'=>'col-md-3 control-label']) }}
														<div class='col-md-9'>
															{{ Form::select('patient_work_city',$city, null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50','id'=>'patient_work_city']) }}
															@if ($errors->has('patient_work_city')) <p class="help-block">{{ $errors->first('patient_work_city') }}</p> @endif
														</div>
												</div>
											</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_work_state')) has-error @endif'>
										{{ Form::label('State', 'State',['class'=>'col-md-3 control-label']) }}
										<div class='col-md-9'>
											{{ Form::select('patient_work_state', $state, null, ['class'=>'form-control','maxlength'=>'10','id'=>'patient_work_state']) }}
											@if ($errors->has('patient_work_state')) <p class="help-block">{{ $errors->first('patient_work_state') }}</p> @endif
										</div>
									</div>
									<div class='form-group  @if ($errors->has('patient_work_country')) has-error @endif'>
										{{ Form::label('Country', 'Country',['class'=>'col-md-3 control-label']) }}
										<div class='col-md-9'>
											{{ Form::select('patient_work_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
											@if ($errors->has('patient_work_country')) <p class="help-block">{{ $errors->first('patient_work_country') }}</p> @endif
										</div>
									</div>

								</div>
				</div>
				</div>
			</div>
			<!-- Contact information -->
			<div id="tab-3" class="tab-pane">
				<div class="panel-body">
					<!-- section -->
						<div class="row">
								<div class="col-xs-6">
									<h4>Current Address</h4>
									<hr>
									<div class='form-group  @if ($errors->has('patient_cur_street_1')) has-error @endif'>
										{{ Form::label('Street 1', 'Address 1',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_cur_street_1', null, ['id'=>'patient_cur_street_1','class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_cur_street_1')) <p class="help-block">{{ $errors->first('patient_cur_street_1') }}</p> @endif
										</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_cur_street_2')) has-error @endif'>
										{{ Form::label('Street 2', 'Address 2',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_cur_street_2', null, ['id'=>'patient_cur_street_2','class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_cur_street_2')) <p class="help-block">{{ $errors->first('patient_cur_street_2') }}</p> @endif
										</div>
									</div>

									<div class="row">
											<div class="col-xs-6">
												<div class='form-group  @if ($errors->has('patient_cur_postcode')) has-error @endif'>
														{{ Form::label('Postcode', 'Postcode',['class'=>'col-md-6 control-label']) }}
														<div class='col-md-6'>
															{{ Form::text('patient_cur_postcode', null, ['id'=>'patient_cur_postcode','class'=>'form-control','data-mask'=>'99999','placeholder'=>'','maxlength'=>'5','onblur'=>'current_postcode_change()','id'=>'patient_cur_postcode']) }}
															@if ($errors->has('patient_cur_postcode')) <p class="help-block">{{ $errors->first('patient_cur_postcode') }}</p> @endif
														</div>
												</div>
											</div>
											<div class="col-xs-6">
												<div class='form-group  @if ($errors->has('patient_cur_city')) has-error @endif'>
														{{ Form::label('City', 'City',['class'=>'col-md-3 control-label']) }}
														<div class='col-md-9'>
															{{ Form::select('patient_cur_city',$city, null, ['id'=>'patient_cur_city','class'=>'form-control','placeholder'=>'','maxlength'=>'50','id'=>'patient_cur_city']) }}
															@if ($errors->has('patient_cur_city')) <p class="help-block">{{ $errors->first('patient_cur_city') }}</p> @endif
														</div>
												</div>
											</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_cur_state')) has-error @endif'>
										{{ Form::label('State', 'State',['class'=>'col-md-3 control-label']) }}
										<div class='col-md-9'>
											{{ Form::select('patient_cur_state', $state, null, ['id'=>'patient_cur_state','class'=>'form-control','maxlength'=>'10','id'=>'patient_cur_state']) }}
											@if ($errors->has('patient_cur_state')) <p class="help-block">{{ $errors->first('patient_cur_state') }}</p> @endif
										</div>
									</div>
									<div class='form-group  @if ($errors->has('patient_cur_country')) has-error @endif'>
										{{ Form::label('Country', 'Country',['class'=>'col-md-3 control-label']) }}
										<div class='col-md-9'>
											{{ Form::select('patient_cur_country', $nation,null, ['id'=>'patient_cur_country','class'=>'form-control','maxlength'=>'10']) }}
											@if ($errors->has('patient_cur_country')) <p class="help-block">{{ $errors->first('patient_cur_country') }}</p> @endif
										</div>
									</div>

								</div>
								<div class="col-xs-6">
									<h4>Permanent Address</h4>
									<hr>
									<div class='form-group  @if ($errors->has('patient_per_street_1')) has-error @endif'>
										{{ Form::label('Address 1', 'Address 1',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_per_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_per_street_1')) <p class="help-block">{{ $errors->first('patient_per_street_1') }}</p> @endif
										</div>
									</div>

									<div class='form-group  @if ($errors->has('patient_per_street_2')) has-error @endif'>
										{{ Form::label('Address 2', 'Address 2',['class'=>'col-sm-3 control-label']) }}
										<div class='col-sm-9'>
											{{ Form::text('patient_per_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
											@if ($errors->has('patient_per_street_2')) <p class="help-block">{{ $errors->first('patient_per_street_2') }}</p> @endif
										</div>
									</div>

									<div class="row">
											<div class="col-xs-6">
													<div class='form-group  @if ($errors->has('patient_per_postcode')) has-error @endif'>
														{{ Form::label('Postcode', 'Postcode',['class'=>'col-md-6 control-label']) }}
														<div class='col-md-6'>
															{{ Form::text('patient_per_postcode', null, ['class'=>'form-control','data-mask'=>'99999','placeholder'=>'','maxlength'=>'5','id'=>'patient_per_postcode','onblur'=>'permanent_postcode_change()']) }}
															@if ($errors->has('patient_per_postcode')) <p class="help-block">{{ $errors->first('patient_per_postcode') }}</p> @endif
														</div>
													</div>
											</div>
											<div class="col-xs-6">
													 <div class='form-group  @if ($errors->has('patient_per_city')) has-error @endif'>
														{{ Form::label('City', 'City',['class'=>'col-md-3 control-label']) }}
														<div class='col-md-9'>
															{{ Form::select('patient_per_city',$city, null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50','id'=>'patient_per_city']) }}
															@if ($errors->has('patient_per_city')) <p class="help-block">{{ $errors->first('patient_per_city') }}</p> @endif
														</div>
													</div>
											</div>
									</div>
								   
									<div class='form-group  @if ($errors->has('patient_per_state')) has-error @endif'>
										{{ Form::label('State', 'State',['class'=>'col-md-3 control-label']) }}
										<div class='col-md-9'>
											{{ Form::select('patient_per_state', $state, null, ['class'=>'form-control','maxlength'=>'10','id'=>'patient_per_state']) }}
											@if ($errors->has('patient_per_state')) <p class="help-block">{{ $errors->first('patient_per_state') }}</p> @endif
										</div>
									</div>
									<div class='form-group  @if ($errors->has('patient_per_country')) has-error @endif'>
										{{ Form::label('Country', 'Country',['class'=>'col-md-3 control-label']) }}
										<div class='col-md-9'>
											{{ Form::select('patient_per_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
											@if ($errors->has('patient_per_country')) <p class="help-block">{{ $errors->first('patient_per_country') }}</p> @endif
										</div>
									</div>
					
								</div>
						</div>
					<!-- end section -->
					<!-- section -->
					<br>
					<h4>Phone & Email</h4>
					<hr>
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
					<!-- end section -->
		</div>



	


			</div>
			<div id="tab-5" class="tab-pane">
				<div class="panel-body">
					<div class="row">
							<div class="col-xs-12">
									Click on the Upload button to find and attach an image to this patient.
									<br>
									<br>
									<div class='form-group'>
										<div class='col-sm-8'>
											@if (Storage::disk('local')->has('/'.$patient->patient_mrn.'/'.$patient->patient_mrn))	
											<img id='show_image' src='{{ route('patient.image', ['id'=>$patient->patient_mrn]) }}' style='border:2px solid gray' height='200' width='150'>
											@else
													<img id='show_image' src='/profile-img.png' style='border:2px solid gray' height='200' width='150'>
											@endif
											<br>
											<br>
												<label class='btn btn-default'>
														<span class='glyphicon glyphicon-picture' aria-hidden='true'></span>
													Upload<input type='file' name='file' style='display: none;' id='file' onchange='readURL(this);'>
												</label>
										</div>
									</div>
							</div>
					</div>
				<div>	
			<div>
	</div>

<script>

		$('#patient_birthdate').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
				});
		/**
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
		**/

		function readURL(input) {
			if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function (e) {
						$('#show_image')
							.attr('src', e.target.result)
							.width(150)
							.heigt(200);
					};
					reader.readAsDataURL(input.files[0]);
			}
		}

		function current_postcode_change() {
			postcodes = {
					@foreach($postcodes as $postcode)
							'{{$postcode->postcode}}':'{{$postcode->city_code}}:{{$postcode->state_code}}',
					@endforeach
			}
			postcode = $('#patient_cur_postcode').val();
			postcode = document.getElementById('patient_cur_postcode').value;

			
			var city = document.getElementById('patient_cur_city');
			var state = document.getElementById('patient_cur_state');

			value = postcodes[postcode];
			values = value.split(":")

			city.value = values[0]; 
			state.value = values[1]; 
		}	

		function permanent_postcode_change() {
			postcodes = {
					@foreach($postcodes as $postcode)
							'{{$postcode->postcode}}':'{{$postcode->city_code}}:{{$postcode->state_code}}',
					@endforeach
			}
			postcode = $('#patient_per_postcode').val();

			
			var city = document.getElementById('patient_per_city');
			var state = document.getElementById('patient_per_state');

			value = postcodes[postcode];
			values = value.split(":")
			city.value = values[0]; 
			state.value = values[1]; 
		}

		function work_postcode_change() {
			postcodes = {
					@foreach($postcodes as $postcode)
							'{{$postcode->postcode}}':'{{$postcode->city_code}}:{{$postcode->state_code}}',
					@endforeach
			}
			postcode = $('#patient_work_postcode').val();

			
			var city = document.getElementById('patient_work_city');
			var state = document.getElementById('patient_work_state');

			value = postcodes[postcode];
			values = value.split(":")
			city.value = values[0]; 
			state.value = values[1]; 
		}
		$('.clockpicker').clockpicker();
</script>

<script type="text/javascript">
           // var conn;
           var cmd;
           var noSupportMessage = "Your browser cannot support WebSocket!";
           var ws;
           
            $(document).ready(function () {
               
                connectSocketServer();
               // initCanvas();
                

                $('#getInfo').click(function () {;
                    
					show(document.querySelectorAll('.mykad_reading'));
					hide(document.querySelectorAll('.mykad_error'));
                    // ReadCard("ACS ACR128U ICC Interface 0", "C:\\test\\mykad.jpeg")))
                    cmd = "R";
                    var readerName = "IRIS SCR18U 0";
                    var fileName = "C:\\mykad\\photo.jpeg";         
                    ws.send(cmd + "," + readerName + "," + fileName );
                });
                
                //
            });
            
            function initCanvas()
            {
                var canvas = document.getElementById("MyCanvas");
                var ctx = canvas.getContext('2d');
                ctx.fillStyle = "gray";
                ctx.fillRect(0, 0, 150, 200);
            }
            
            function disconnectWebSocket() {
                if (ws) {
                    ws.close();
                }
            }
           
            function connectSocketServer() {
                var support = "MozWebSocket" in window ? 'MozWebSocket' : ("WebSocket" in window ? 'WebSocket' : null);

                if (support == null) {
                    alert("* " + noSupportMessage + "<br/>");
                    return;
                }

                //appendMessage("* Connecting to server ..<br/>");
                // create a new websocket and connect
                ws = new window[support]('ws://localhost:8100/');
                ws.binaryType = "arraybuffer";

                // when data is comming from the server, this metod is called
                ws.onmessage = function (evt) {

                    if(evt.data instanceof ArrayBuffer)
                    {       
                        //drawImage(evt.data);

                    }else
                    {    
                        var data = evt.data.split(',');
                        
                        if(data[0] === '0')
                        {
                            var display = data[1] + '<BR>' + 
                                          data[2] + '<BR>' + 
                                          data[3] + '<BR>' + 
                                          data[4] + '<BR>' + 
                                          data[5] + '<BR>' + 
                                          data[6] + '<BR>' + 
                                          data[7] + '<BR>' +
                                          data[8] + '<BR>' +
                                          data[9] + '<BR>';

							document.getElementById('patient_name').value = cleanData(data[1]);
							document.getElementById('gender_code').value = cleanData(data[3]);

							var patient_new_ic = cleanData(data[2]);
							patient_new_ic = patient_new_ic.substring(0,6) + "-" + patient_new_ic.substring(6,8) + "-" + patient_new_ic.substring(8,12);
							document.getElementById('patient_new_ic').value = patient_new_ic;

							document.getElementById('patient_cur_street_1').value = cleanData(data[4]) + " " + cleanData(data[5]);
							document.getElementById('patient_cur_street_2').value = cleanData(data[6]);
							document.getElementById('patient_cur_city').value = cleanData(data[7]);
							document.getElementById('patient_cur_postcode').value = cleanData(data[8]);
							document.getElementById('patient_cur_state').value = cleanData(data[9]);
							current_postcode_change();
							hide(document.querySelectorAll('.mykad_reading'));
							show(document.querySelectorAll('.mykad_complete'));
                        }else{
							hide(document.querySelectorAll('.mykad_reading'));
							show(document.querySelectorAll('.mykad_error'));
                        }
                    }
                };

                // when the connection is established, this method is called
                ws.onopen = function () {
                };

                // when the connection is closed, this method is called
                ws.onclose = function () {

                };
            }
            
			function cleanData(data) 
			{
					for (i=0;i<data.length;i++) {
							if (data.charCodeAt(i)==0) {
									data = data.substring(0,i);
									return data.trim();
							}
					}
					return data.trim();
			}

            function UploadImage()
            {
                //Convert image to canvas
                var canvas = document.createElement('canvas');
                canvas.width = 150;
                canvas.height = 200;
                var ctx = canvas.getContext('2d');
                var img=document.getElementById("show_image");
                ctx.drawImage(img,0,0);
                
                var canvasData = canvas.toDataURL("image/jpeg");
                
                //Send canvas data to server
                $.ajax({
                    type: "POST",
                    url: "script.php",
                    data: { 
                       imgBase64: canvasData
                    }
                  }).done(function(o) {
                    console.log('saved'); 
                    // If you want the file to be visible in the browser 
                    // - please modify the callback in javascript. All you
                    // need is to return the url to the file, you just saved 
                    // and than put the image in your browser.
                  });
                  
                  /*PHP code
                   * $img = $_POST['data'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $fileData = base64_decode($img);
                    //saving
                    $fileName = 'photo.jpeg';
                    file_put_contents($fileName, $fileData);
                   */
                
            }
  
            function drawImage(data) {
                
                var byteArray = new Uint8Array(data);           
                var image = document.getElementById('show_image');
                image.src = 'data:image/jpeg;base64,'+encode(byteArray);
            }
            
            function encode (input) {
            var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;

            while (i < input.length) {
                chr1 = input[i++];
                chr2 = i < input.length ? input[i++] : Number.NaN; // Not sure if the index 
                chr3 = i < input.length ? input[i++] : Number.NaN; // checks are needed here

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
                output += keyStr.charAt(enc1) + keyStr.charAt(enc2) +
                          keyStr.charAt(enc3) + keyStr.charAt(enc4);
            }
            return output;
        }
            
            
	hide(document.querySelectorAll('.mykad_reading'));
	hide(document.querySelectorAll('.mykad_complete'));
	hide(document.querySelectorAll('.mykad_error'));

	function hide (elements) {
		elements = elements.length ? elements : [elements];
		for (var index = 0; index < elements.length; index++) {
			elements[index].style.display = 'none';
		}
	}

	function show (elements, specifiedDisplay) {
		var computedDisplay, element, index;

		elements = elements.length ? elements : [elements];
		for (index = 0; index < elements.length; index++) {
				element = elements[index];

				element.style.display = '';
				computedDisplay = window.getComputedStyle(element, null).getPropertyValue('display');

				if (computedDisplay === 'none') {
				element.style.display = specifiedDisplay || 'block';
				}
		}
	}
</script>
