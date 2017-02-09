
<h1>Newborn Registration</h1>
<br>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('patient_birthdate')) has-error @endif'>
						{{ Form::label('Birthdate', 'Birthdate',['class'=>'col-md-6 control-label']) }}
						
						<div class='col-md-6'>
							<div class="input-group date">
								{{ Form::text('patient_birthdate', DojoUtility::dateReadFormat($patient_newborn->patient_birthdate), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'patient_birthdate',]) }}
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
						{{ Form::label('Birthtime', 'Birthtime',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							<!--
							<input id="patient_birthtime" name="patient_birthtime" type="text">
							@if ($errors->has('patient_birthtime')) <p class="help-block">{{ $errors->first('patient_birthtime') }}</p> @endif
							-->
								<div id="patient_birthtime" name="patient_birthtime" class="input-group clockpicker" data-autoclose="true">
										{{ Form::text('patient_birthtime', $patient_newborn->patient_birthtime, ['class'=>'form-control','data-mask'=>'99:99']) }}
										<span class="input-group-addon">
											<span class="fa fa-clock-o"></span>
										</span>
								</div>

						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-3">
					<div class='form-group  @if ($errors->has('newborn_gestational_weeks')) has-error @endif'>
						{{ Form::label('newborn_gestational_weeks', 'Gestational Age',['class'=>'col-sm-12 control-label']) }}
					</div>
			</div>
			<div class="col-xs-9">
					<div class='form-group  @if ($errors->has('newborn_gestational_weeks')) has-error @endif'>
						<div class='col-sm-2'>
							{{ Form::text('newborn_gestational_weeks', null, ['class'=>'form-control','data-mask'=>'9*','placeholder'=>'Weeks']) }}
							@if ($errors->has('newborn_gestational_weeks')) <p class="help-block">{{ $errors->first('newborn_gestational_weeks') }}</p> @endif
						</div>
						<div class='col-sm-2'>
							{{ Form::text('newborn_gestational_days', null, ['class'=>'form-control','data-mask'=>'9','placeholder'=>'Days']) }}
							@if ($errors->has('newborn_gestational_weeks')) <p class="help-block">{{ $errors->first('newborn_gestational_weeks') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('gender_code')) has-error @endif'>
						<label for='gender_code' class='col-sm-6 control-label'>Gender<span style='color:red;'> *</span></label>
						<div class='col-sm-6'>
							{{ Form::select('gender_code', $gender,$patient_newborn->gender_code, ['class'=>'form-control','maxlength'=>'1']) }}
							@if ($errors->has('gender_code')) <p class="help-block">{{ $errors->first('gender_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_weight')) has-error @endif'>
						<label class='col-sm-6 control-label'>Weight<span style='color:red;'> *</span></label>
						<div class='col-sm-6'>
							{{ Form::text('newborn_weight', null, ['class'=>'form-control','placeholder'=>'kg',]) }} 
							@if ($errors->has('newborn_weight')) <p class="help-block">{{ $errors->first('newborn_weight') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_length')) has-error @endif'>
						<label class='col-sm-6 control-label'>Length<span style='color:red;'> *</span></label>
						<div class='col-sm-6'>
							{{ Form::text('newborn_length', null, ['class'=>'form-control','placeholder'=>'cm',]) }}
							@if ($errors->has('newborn_length')) <p class="help-block">{{ $errors->first('newborn_length') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_head_circumferance')) has-error @endif'>
						<label class='col-sm-6 control-label'>Head Circumference<span style='color:red;'> *</span></label>
						<div class='col-sm-6'>
							{{ Form::text('newborn_head_circumferance', null, ['class'=>'form-control','placeholder'=>'cm',]) }}
							@if ($errors->has('newborn_head_circumferance')) <p class="help-block">{{ $errors->first('newborn_head_circumferance') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('birth_code')) has-error @endif'>
						{{ Form::label('birth_code', 'Birth Type',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::select('birth_code', $birth,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('birth_code')) <p class="help-block">{{ $errors->first('birth_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('complication_code')) has-error @endif'>
						{{ Form::label('complication_code', 'Complication',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::select('complication_code', $complication,null, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('complication_code')) <p class="help-block">{{ $errors->first('complication_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

   <div class='form-group  @if ($errors->has('delivery_code')) has-error @endif'>
		<label class='col-sm-3 control-label'>Delivery Mode<span style='color:red;'> *</span></label>
		<div class='col-sm-9'>
			{{ Form::select('delivery_code', $delivery,null, ['class'=>'form-control','maxlength'=>'20']) }}
			@if ($errors->has('delivery_code')) <p class="help-block">{{ $errors->first('delivery_code') }}</p> @endif
		</div>
	</div>

	<h3>Apgar Score at 1'</h3>
	<hr>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_heart_rate')) has-error @endif'>
						{{ Form::label('apgar_heart_rate', 'Heart Rate',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_heart_rate', $apgar_values,null, ['id'=>'apgar_heart_rate','class'=>'form-control','onchange'=>'apgarChanged()']) }}
							@if ($errors->has('apgar_heart_rate')) <p class="help-block">{{ $errors->first('apgar_heart_rate') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_breathing')) has-error @endif'>
						{{ Form::label('apgar_breathing', 'Breathing',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_breathing', $apgar_values,null, ['id'=>'apgar_breathing','class'=>'form-control','onchange'=>'apgarChanged()']) }}
							@if ($errors->has('apgar_breathing')) <p class="help-block">{{ $errors->first('apgar_breathing') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_grimace')) has-error @endif'>
						{{ Form::label('apgar_grimace', 'Grimace',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_grimace', $apgar_values,null, ['id'=>'apgar_grimace','class'=>'form-control','onchange'=>'apgarChanged()']) }}
							@if ($errors->has('apgar_grimace')) <p class="help-block">{{ $errors->first('apgar_grimace') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_activity')) has-error @endif'>
						{{ Form::label('apgar_activity', 'Activity',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_activity', $apgar_values,null, ['id'=>'apgar_activity','class'=>'form-control','onchange'=>'apgarChanged()']) }}
							@if ($errors->has('apgar_activity')) <p class="help-block">{{ $errors->first('apgar_activity') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_appearance')) has-error @endif'>
						{{ Form::label('apgar_appearance', 'Appearance',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::select('apgar_appearance', $apgar_values,null, ['id'=>'apgar_appearance','class'=>'form-control','onchange'=>'apgarChanged()']) }}
							@if ($errors->has('apgar_appearance')) <p class="help-block">{{ $errors->first('apgar_appearance') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_apgar')) has-error @endif'>
						{{ Form::label('newborn_apgar', 'Apgar Score',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::text('newborn_apgar', null, ['id'=>'newborn_apgar','class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('newborn_apgar')) <p class="help-block">{{ $errors->first('newborn_apgar') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<h3>Apgar Score at 5'</h3>
	<hr>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_heart_rate_5')) has-error @endif'>
						{{ Form::label('apgar_heart_rate_5', 'Heart Rate',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_heart_rate_5', $apgar_values,null, ['id'=>'apgar_heart_rate_5','class'=>'form-control','onchange'=>'apgarChanged5()']) }}
							@if ($errors->has('apgar_heart_rate_5')) <p class="help-block">{{ $errors->first('apgar_heart_rate_5') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_breathing_5')) has-error @endif'>
						{{ Form::label('apgar_breathing_5', 'Breathing',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_breathing_5', $apgar_values,null, ['id'=>'apgar_breathing_5','class'=>'form-control','onchange'=>'apgarChanged5()']) }}
							@if ($errors->has('apgar_breathing_5')) <p class="help-block">{{ $errors->first('apgar_breathing_5') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_grimace_5')) has-error @endif'>
						{{ Form::label('apgar_grimace_5', 'Grimace',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_grimace_5', $apgar_values,null, ['id'=>'apgar_grimace_5','class'=>'form-control','onchange'=>'apgarChanged5()']) }}
							@if ($errors->has('apgar_grimace_5')) <p class="help-block">{{ $errors->first('apgar_grimace_5') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_activity_5')) has-error @endif'>
						{{ Form::label('apgar_activity_5', 'Activity',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
            				{{ Form::select('apgar_activity_5', $apgar_values,null, ['id'=>'apgar_activity_5','class'=>'form-control','onchange'=>'apgarChanged5()']) }}
							@if ($errors->has('apgar_activity_5')) <p class="help-block">{{ $errors->first('apgar_activity_5') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('apgar_appearance_5')) has-error @endif'>
						{{ Form::label('apgar_appearance_5', 'Appearance',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::select('apgar_appearance_5', $apgar_values,null, ['id'=>'apgar_appearance_5','class'=>'form-control','onchange'=>'apgarChanged5()']) }}
							@if ($errors->has('apgar_appearance_5')) <p class="help-block">{{ $errors->first('apgar_appearance_5') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_apgar_5')) has-error @endif'>
						{{ Form::label('newborn_apgar_5', 'Apgar Score',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::text('newborn_apgar_5', null, ['id'=>'newborn_apgar_5','class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('newborn_apgar_5')) <p class="help-block">{{ $errors->first('newborn_apgar_5') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<h3>Procedure</h3>
	<hr>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_g6pd')) has-error @endif'>
						{{ Form::label('newborn_g6pd', 'G6PD',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							<div class="input-group date">
								{{ Form::text('newborn_g6pd', DojoUtility::dateReadFormat($newborn->newborn_g6pd), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'newborn_g6pd',]) }}

								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('newborn_g6pd')) <p class="help-block">{{ $errors->first('newborn_g6pd') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_hepatitis_b')) has-error @endif'>
						{{ Form::label('newborn_hepatitis_b', 'Hepatitis B',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							<div class="input-group date">
								{{ Form::text('newborn_hepatitis_b', DojoUtility::dateReadFormat($newborn->newborn_hepatitis_b), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'newborn_hepatitis_b',]) }}
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('newborn_hepatitis_b')) <p class="help-block">{{ $errors->first('newborn_hepatitis_b') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_bcg')) has-error @endif'>
						{{ Form::label('newborn_bcg', 'BCG',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							<div class="input-group date">
								{{ Form::text('newborn_bcg', DojoUtility::dateReadFormat($newborn->newborn_bcg), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'newborn_bcg',]) }}
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('newborn_bcg')) <p class="help-block">{{ $errors->first('newborn_bcg') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_vitamin_k')) has-error @endif'>
						{{ Form::label('newborn_vitamin_k', 'Vitamin K',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							<div class="input-group date">
								{{ Form::text('newborn_vitamin_k', DojoUtility::dateReadFormat($newborn->newborn_vitamin_k), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'newborn_vitamin_k',]) }}
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('newborn_vitamin_k')) <p class="help-block">{{ $errors->first('newborn_vitamin_k') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


    <div class='form-group  @if ($errors->has('newborn_thyroid')) has-error @endif'>
        {{ Form::label('newborn_thyroid', 'Thyroid Screening',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-3'>
			<div class="input-group date">
				{{ Form::text('newborn_thyroid', DojoUtility::dateReadFormat($newborn->newborn_thyroid), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'newborn_thyroid',]) }}
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
            @if ($errors->has('newborn_thyroid')) <p class="help-block">{{ $errors->first('newborn_thyroid') }}</p> @endif
        </div>
    </div>


	<br>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/newborns?id={{ $consultation->consultation_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::hidden('encounter_id', null) }}
    {{ Form::hidden('consultation_id', $consultation->consultation_id) }}
	<script>
		$('#newborn_g6pd').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('#newborn_hepatitis_b').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});


		$('#newborn_bcg').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});


		$('#newborn_vitamin_k').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});


		$('#newborn_thyroid').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});


		$('#patient_birthdate').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		$('.clockpicker').clockpicker();

		function apgarChanged() {
				var score=0;
				var apgar = document.getElementById('apgar_heart_rate');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_breathing');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_grimace');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_activity');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_appearance');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				document.getElementById('newborn_apgar').value = score;
		}

		function apgarChanged5() {
				var score=0;
				var apgar = document.getElementById('apgar_heart_rate_5');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_breathing_5');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_grimace_5');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_activity_5');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				var apgar = document.getElementById('apgar_appearance_5');
				score += parseInt(apgar.options[apgar.selectedIndex].value);
				document.getElementById('newborn_apgar_5').value = score;
		}

		document.getElementById('newborn_apgar').disabled = true;
		document.getElementById('newborn_apgar_5').disabled = true;
		apgarChanged();
		apgarChanged5();
	</script>
