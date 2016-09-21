
    <div class='form-group  @if ($errors->has('waste_date')) has-error @endif'>
        <label for='waste_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<input id="waste_date" name="waste_date" type="text">
            @if ($errors->has('waste_date')) <p class="help-block">{{ $errors->first('waste_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('ward_code', $ward,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
        {{ Form::label('period_code', 'Period',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('waste_unit')) has-error @endif'>
        <label for='waste_unit' class='col-sm-3 control-label'>Amount<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('waste_unit', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('waste_unit')) <p class="help-block">{{ $errors->first('waste_unit') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('waste_note')) has-error @endif'>
        {{ Form::label('waste_note', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('waste_note', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('waste_note')) <p class="help-block">{{ $errors->first('waste_note') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/diet_wastages" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	<script>
		$(function(){
				$('#waste_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $diet_wastage->waste_date }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
	</script>
