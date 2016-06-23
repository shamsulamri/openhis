
    <div class='form-group  @if ($errors->has('discharge_date')) has-error @endif'>
        <label for='discharge_date' class='col-sm-2 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			<input id="discharge_date" name="discharge_date" type="text">
            @if ($errors->has('discharge_date')) <p class="help-block">{{ $errors->first('discharge_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        <label for='type_code' class='col-sm-2 control-label'>Outcome<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('type_code', $type,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('type_code')) <p class="help-block">{{ $errors->first('type_code') }}</p> @endif
        </div>
    </div>

	@if ($consultation->encounter->encounter_code=='inpatient')
    <div class='form-group  @if ($errors->has('discharge_diagnosis')) has-error @endif'>
        {{ Form::label('discharge_diagnosis', 'Diagnosis',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('discharge_diagnosis', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_diagnosis')) <p class="help-block">{{ $errors->first('discharge_diagnosis') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('discharge_summary')) has-error @endif'>
        {{ Form::label('discharge_summary', 'Summary',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('discharge_summary', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_summary')) <p class="help-block">{{ $errors->first('discharge_summary') }}</p> @endif
        </div>
    </div>
	@endif

    <div class='form-group'>
        {{ Form::label('mc', 'Medical Certificate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
		@if ($mc)
        		{{ Form::label('product', $mc->getMcStart()->format('d M Y'),['class'=>'control-label']) }}<br>
				@if (empty($mc->mc_end))
        		{{ Form::label('mc', 'End: '.$mc->mc_end,['class'=>'control-label']) }}<br>
				@endif
        		{{ Form::label('mc', 'Serial Number: '.$mc->mc_identification,['class'=>'control-label']) }}
				@else
        		{{ Form::label('mc', 'None',['class'=>'control-label']) }}
		@endif
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('discharge_orders', 'Discharge Orders',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
		@if (count($discharge_orders)>0)
			@foreach ($discharge_orders as $order)
        		{{ Form::label('product', $order->product_name,['class'=>'control-label']) }}
				<br>
			@endforeach
			<br>
		@else
			<div class='alert alert-warning'>
			<strong>Warning !</strong> No discharge order.
			</div>
		@endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Discharge Patient', ['class'=>'btn btn-primary']) }}
        </div>
    </div>


	{{ Form::hidden('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
	{{ Form::hidden('consultation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
	{{ Form::hidden('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}

	<script>
		$(function(){
				$('#discharge_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $discharge->discharge_date }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
	</script>
