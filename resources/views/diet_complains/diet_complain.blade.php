
    <div class='form-group  @if ($errors->has('patient_mrn')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Patient MRN<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('patient_mrn', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_mrn')) <p class="help-block">{{ $errors->first('patient_mrn') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('complain_date')) has-error @endif'>
        <label for='complain_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<input id="complain_date" name="complain_date" type="text">
            @if ($errors->has('complain_date')) <p class="help-block">{{ $errors->first('complain_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
        <label for='period_code' class='col-sm-3 control-label'>Period<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('meal_code')) has-error @endif'>
        <label for='meal_code' class='col-sm-3 control-label'>Meal<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('meal_code', $meal,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('meal_code')) <p class="help-block">{{ $errors->first('meal_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('contaminate_meal_other')) has-error @endif'>
        {{ Form::label('contaminate_meal_other', 'Other',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('contaminate_meal_other', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('contaminate_meal_other')) <p class="help-block">{{ $errors->first('contaminate_meal_other') }}</p> @endif
			<small>If not listed above please specify the meal</small>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('contamination_code')) has-error @endif'>
        <label for='contamination_code' class='col-sm-3 control-label'>Contamination<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('contamination_code', $contamination,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('contamination_code')) <p class="help-block">{{ $errors->first('contamination_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('complain_contaminate_other')) has-error @endif'>
        {{ Form::label('complain_contaminate_other', 'Other',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('complain_contaminate_other', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('complain_contaminate_other')) <p class="help-block">{{ $errors->first('complain_contaminate_other') }}</p> @endif
			<small>If not listed above please specify the contamination</small>
        </div>
    </div>

    <div class='form-group  @if ($errors->has('complain_other')) has-error @endif'>
        {{ Form::label('complain_other', 'Other Complain',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('complain_other', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('complain_other')) <p class="help-block">{{ $errors->first('complain_other') }}</p> @endif
        </div>
    </div>

	<hr>
    <div class='form-group  @if ($errors->has('complain_reported')) has-error @endif'>
        {{ Form::label('complain_reported', 'Complain Reported',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('complain_reported', '1') }}
            @if ($errors->has('complain_reported')) <p class="help-block">{{ $errors->first('complain_reported') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('complain_action')) has-error @endif'>
        {{ Form::label('complain_action', 'Action',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('complain_action', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('complain_action')) <p class="help-block">{{ $errors->first('complain_action') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/diet_complains" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	<script>
		$(function(){
				$('#complain_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $diet_complain->complain_date }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
	</script>
