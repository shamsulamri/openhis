
	<br>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('mc_start')) has-error @endif'>
						<label for='mc_start' class='col-sm-4 control-label'>Date Start<span style='color:red;'> *</span></label>
						<div class='col-sm-7'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="mc_start" id="mc_start" type="text" class="form-control" value="{{ $medical_certificate->mc_start }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('mc_start')) <p class="help-block">{{ $errors->first('mc_start') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('mc_end')) has-error @endif'>
						<label for='mc_end' class='col-sm-5 control-label'>Date End</label>
						<div class='col-sm-7'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="mc_end" id="mc_end" type="text" class="form-control" value="{{ $medical_certificate->mc_end }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('mc_end')) <p class="help-block">{{ $errors->first('mc_end') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('mc_time_start')) has-error @endif'>
						<label for='mc_time_start' class='col-sm-4 control-label'>Time Start</label>
						<div class='col-sm-7'>
							<div id="mc_time_start" name="mc_time_start" class="input-group clockpicker" data-autoclose="true">
								{{ Form::text('mc_time_start', null, ['class'=>'form-control','data-mask'=>'99:99']) }}
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
							</div>
							@if ($errors->has('mc_time_start')) <p class="help-block">{{ $errors->first('mc_time_start') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('mc_time_end')) has-error @endif'>
						<label for='mc_time_end' class='col-sm-5 control-label'>Time End</label>
						<div class='col-sm-7'>
							<div id="mc_time_end" name="mc_time_end" class="input-group clockpicker" data-autoclose="true">
								{{ Form::text('mc_time_end', null, ['class'=>'form-control','data-mask'=>'99:99']) }}
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
							</div>
							@if ($errors->has('mc_time_end')) <p class="help-block">{{ $errors->first('mc_time_end') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


    <div class='form-group  @if ($errors->has('mc_identification')) has-error @endif'>
        <label for='mc_identification' class='col-sm-2 control-label'>Serial Number</label>
        <div class='col-sm-10'>
            {{ Form::text('mc_identification', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('mc_identification')) <p class="help-block">{{ $errors->first('mc_identification') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mc_notes')) has-error @endif'>
        {{ Form::label('mc_notes', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('mc_notes', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('mc_notes')) <p class="help-block">{{ $errors->first('mc_notes') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
            {{ Form::hidden('encounter_id', null) }}
            {{ Form::hidden('consultation_id', $consultation->consultation_id) }}


	<script>
		$('#mc_start').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('#mc_end').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('.clockpicker').clockpicker();
		$(function(){
				$('#mc_time_start').combodate({
						format: "HH:mm",
						template: "HH : mm",
						value: '{{ $medical_certificate->mc_time_start }}',
						minuteStep: 1,
						customClass: 'select'
				});    
		});

		$(function(){
				$('#mc_time_end').combodate({
						format: "HH:mm",
						template: "HH : mm",
						value: '{{ $medical_certificate->mc_time_end }}',
						minuteStep: 1,
						customClass: 'select'
				});    
		});
	</script>
