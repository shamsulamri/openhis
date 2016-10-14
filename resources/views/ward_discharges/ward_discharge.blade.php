
    <div class='form-group  @if ($errors->has('discharge_description')) has-error @endif'>
        {{ Form::label('discharge_description', 'Payment',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if (count($bill))
        		{{ Form::label('payment', 'Paid',['class'=>'control-label']) }}<br>
			@else
				<p class='text-danger'>
				{{ Form::label('mc', 'Not Paid',['class'=>'control-label']) }}
				</p>
			@endif
        </div>
    </div>


    <div class='form-group'>
        {{ Form::label('mc', 'Medical Certificate',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
		@if (count($mc)>0)
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
        {{ Form::label('mc', 'Next Appointment',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if (count($appointments)>0)
					@foreach ($appointments as $appointment)
						<label class='control-label'>
						{{ date('d F Y, H:i', strtotime($appointment->appointment_datetime)) }} with 
						{{ $appointment->service->service_name }}
						</label>
						<br>
					@endforeach
			<br>
			@endif
			<a href='/appointment_services/{{ $patient->patient_id }}/0/{{ $service_id }}?admission_id={{ $admission_id }}' class='btn btn-default'>
				New Appointment
			</a>
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('discharge_orders', 'Discharge Orders',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
		@if (count($discharge_orders)>0)
			@foreach ($discharge_orders as $order)
        		{{ Form::label('product', $order->product->product_name,['class'=>'control-label']) }}
				<br>
			@endforeach
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
