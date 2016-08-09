
<h1>Newborn Registration</h1>
<br>
	<div class='form-group  @if ($errors->has('patient_birthdate')) has-error @endif'>
		{{ Form::label('Birthdate', 'Birthdate',['class'=>'col-md-3 control-label']) }}
		<div class='col-md-9'>
			<input id="patient_birthdate" name="patient_birthdate" type="text">
			&nbsp;{{ Form::label('time','Time',['class'=>'control-label']) }}
			<input id="patient_birthtime" name="patient_birthtime" type="text">
			@if ($errors->has('patient_birthdate')) <p class="help-block">{{ $errors->first('patient_birthdate') }}</p> @endif
		</div>
	</div>
	<div class="row">
			<div class="col-xs-3">
					<div class='form-group  @if ($errors->has('newborn_gestational_weeks')) has-error @endif'>
						{{ Form::label('newborn_gestational_weeks', 'Gestational Age',['class'=>'col-sm-12 control-label']) }}
					</div>
			</div>
			<div class="col-xs-3">
					<div class='form-group  @if ($errors->has('newborn_gestational_weeks')) has-error @endif'>
						<div class='col-sm-8'>
							{{ Form::text('newborn_gestational_weeks', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('newborn_gestational_weeks')) <p class="help-block">{{ $errors->first('newborn_gestational_weeks') }}</p> @endif
						</div>
						{{ Form::label('week', 'Weeks',['class'=>'control-label']) }}
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group  @if ($errors->has('newborn_gestational_days')) has-error @endif'>
						<div class='col-sm-5'>
							{{ Form::text('newborn_gestational_days', null, ['class'=>'col-sm-5 form-control','placeholder'=>'',]) }}
							@if ($errors->has('newborn_gestational_days')) <p class="help-block">{{ $errors->first('newborn_gestational_days') }}</p> @endif
						</div>
						{{ Form::label('day', 'Days',['class'=>'control-label']) }}
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
						{{ Form::label('newborn_weight', 'Weight',['class'=>'col-sm-6 control-label']) }}
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
						{{ Form::label('newborn_length', 'Length',['class'=>'col-sm-6 control-label']) }}
						<div class='col-sm-6'>
							{{ Form::text('newborn_length', null, ['class'=>'form-control','placeholder'=>'cm',]) }}
							@if ($errors->has('newborn_length')) <p class="help-block">{{ $errors->first('newborn_length') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('newborn_head_circumferance')) has-error @endif'>
						{{ Form::label('newborn_head_circumferance', 'Head Circumferance',['class'=>'col-sm-6 control-label']) }}
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
		{{ Form::label('delivery_code', 'Delivery Mode',['class'=>'col-sm-3 control-label']) }}
		<div class='col-sm-9'>
			{{ Form::select('delivery_code', $delivery,null, ['class'=>'form-control','maxlength'=>'20']) }}
			@if ($errors->has('delivery_code')) <p class="help-block">{{ $errors->first('delivery_code') }}</p> @endif
		</div>
	</div>

	<div class='page-header'>
		<h3>Apgar Score</h3>
	</div>

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


	<div class='page-header'>
		<h3>Procedure</h3>
	</div>

    <div class='form-group  @if ($errors->has('newborn_g6pd')) has-error @endif'>
        {{ Form::label('newborn_g6pd', 'G6PD',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			<input id="newborn_g6pd" name="newborn_g6pd" type="text">
			<a href='javascript:set_g6pd()' class='btn btn-default btn-xs'>Today</a>
            @if ($errors->has('newborn_g6pd')) <p class="help-block">{{ $errors->first('newborn_g6pd') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_hepatitis_b')) has-error @endif'>
        {{ Form::label('newborn_hepatitis_b', 'Hepatitis B',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			<input id="newborn_hepatitis_b" name="newborn_hepatitis_b" type="text">
			<a href='javascript:set_hepatitis_b()' class='btn btn-default btn-xs'>Today</a>
            @if ($errors->has('newborn_hepatitis_b')) <p class="help-block">{{ $errors->first('newborn_hepatitis_b') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_bcg')) has-error @endif'>
        {{ Form::label('newborn_bcg', 'BCG',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			<input id="newborn_bcg" name="newborn_bcg" type="text">
			<a href='javascript:set_bcg()' class='btn btn-default btn-xs'>Today</a>
            @if ($errors->has('newborn_bcg')) <p class="help-block">{{ $errors->first('newborn_bcg') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_vitamin_k')) has-error @endif'>
        {{ Form::label('newborn_vitamin_k', 'Vitamin K',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			<input id="newborn_vitamin_k" name="newborn_vitamin_k" type="text">
			<a href='javascript:set_vitamin_k()' class='btn btn-default btn-xs'>Today</a>
            @if ($errors->has('newborn_vitamin_k')) <p class="help-block">{{ $errors->first('newborn_vitamin_k') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('newborn_thyroid')) has-error @endif'>
        {{ Form::label('newborn_thyroid', 'Thyroid Screening',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			<input id="newborn_thyroid" name="newborn_thyroid" type="text">
			<a href='javascript:set_thyroid()' class='btn btn-default btn-xs'>Today</a>
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
		$(function(){
				$('#newborn_g6pd').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $newborn->newborn_g6pd }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});

		$(function(){
				$('#newborn_hepatitis_b').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $newborn->newborn_hepatitis_b }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
		$(function(){
				$('#newborn_bcg').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $newborn->newborn_bcg }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
		$(function(){
				$('#newborn_vitamin_k').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $newborn->newborn_vitamin_k }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
		$(function(){
				$('#newborn_thyroid').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $newborn->newborn_thyroid }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});

		$(function(){
				$('#patient_birthdate').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $patient_newborn->patient_birthdate }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});

		$(function(){
				$('#patient_birthtime').combodate({
						format: "HH:mm",
						template: "HH : mm",
						value: '{{ $patient_newborn->patient_birthtime }}',
						minuteStep: 1,
						customClass: 'select'
				});    
		});
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
		function set_g6pd() {
				$('#newborn_g6pd').combodate('setValue','{{ $today }}');
		}
		function set_hepatitis_b() {
				$('#newborn_hepatitis_b').combodate('setValue','{{ $today }}');
		}
		function set_bcg() {
				$('#newborn_bcg').combodate('setValue','{{ $today }}');
		}
		function set_vitamin_k() {
				$('#newborn_vitamin_k').combodate('setValue','{{ $today }}');
		}
		function set_thyroid() {
				$('#newborn_thyroid').combodate('setValue','{{ $today }}');
		}
		document.getElementById('newborn_apgar').disabled = true;
		apgarChanged();
	</script>
