
<h2>Medical Certificate</h2>
<br>
    <div class='form-group  @if ($errors->has('mc_start')) has-error @endif'>
        <label for='mc_start' class='col-sm-2 control-label'>Date Start<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			<input id="mc_start" name="mc_start" type="text">
            @if ($errors->has('mc_start')) <p class="help-block">{{ $errors->first('mc_start') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mc_end')) has-error @endif'>
        <label for='mc_end' class='col-sm-2 control-label'>Date End</label>
        <div class='col-sm-10'>
			<input id="mc_end" name="mc_end" type="text">
            @if ($errors->has('mc_end')) <p class="help-block">{{ $errors->first('mc_end') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('mc_identification')) has-error @endif'>
        <label for='mc_identification' class='col-sm-2 control-label'>Serial Number<span style='color:red;'> *</span></label>
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
		$(function(){
				$('#mc_start').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $medical_certificate->mc_start }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});

		$(function(){
				$('#mc_end').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $medical_certificate->mc_end }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
	</script>
