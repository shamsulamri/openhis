
    <div class='form-group  @if ($errors->has('discharge_description')) has-error @endif'>
        {{ Form::label('bill', 'Bill',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($bill)
					{{ Form::label('status', 'Paid on '.DojoUtility::dateReadFormat($bill->created_at).' (Id:'.$bill->id.')',['class'=>'control-label']) }}
			@else
					{{ Form::label('status', 'Not Paid' ,['class'=>'control-label']) }}
			@endif
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('mc', 'Next Appointment',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if (count($appointments)>0)
					@foreach ($appointments as $appointment)
						<label class='control-label'>
						<!--
						<a href='{{ URL::to('appointment_services/'. $appointment->patient_id . '/0/'.$appointment->service_id. '/'.$appointment->appointment_id) }}?admission_id={{ $admission_id }}'>
						-->	
						{{ date('d F Y, H:i', strtotime($appointment->appointment_datetime)) }} with 
						{{ $appointment->service->service_name }}
						<!--
						</a>
						-->
						</label>
						<br>
					@endforeach
			@else
				{{ Form::label('mc', 'None',['class'=>'control-label']) }}
				<br>
			@endif
			<!--
			<a href='/appointment_services/{{ $patient->patient_id }}/0/{{ $service_id }}?admission_id={{ $admission_id }}' class='btn btn-default'>
				New Appointment
			</a>
			-->
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('mc', 'Medical Certificate',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
		@if (count($mc)>0)
        		{{ Form::label('mc', DojoUtility::dateReadFormat($mc->mc_start),['class'=>'control-label']) }}
				@if (!empty($mc->mc_end))
        		{{ Form::label('mc', ' to '.DojoUtility::dateReadFormat($mc->mc_end),['class'=>'control-label']) }}
				@endif
		@else
				{{ Form::label('mc', 'None',['class'=>'control-label']) }}
		@endif
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('discharge_orders', 'Discharge Orders',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
		@if (count($discharge_orders)>0)
			<div algin='left'>
			@foreach ($discharge_orders as $order)
        		{{ Form::label('product', "- ".$order->product->product_name) }}
				<br>
			@endforeach
			</div>
			<br>
		@else
        	{{ Form::label('product', 'None',['class'=>'control-label']) }}
			
		@endif
        </div>
    </div>


    <div class='form-group  @if ($errors->has('discharge_description')) has-error @endif'>
        {{ Form::label('discharge_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('discharge_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_description')) <p class="help-block">{{ $errors->first('discharge_description') }}</p> @endif
        </div>
    </div>


    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
            {{ Form::hidden('encounter_id', $ward_discharge->encounter_id)  }}
            {{ Form::hidden('admission_id', $admission_id)  }}
