
    <div class='form-group  @if ($errors->has('discharge_description')) has-error @endif'>
        {{ Form::label('discharge_description', 'Payment',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
			@if (count($bill))
        		{{ Form::label('payment', 'Payment Completed',['class'=>'control-label']) }}<br>
			@else
				<p class='text-danger'>
				Not Paid
				</p>
			@endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('discharge_description')) has-error @endif'>
        {{ Form::label('discharge_description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('discharge_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_description')) <p class="help-block">{{ $errors->first('discharge_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('mc', 'Medical Certificate',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
		@if (count($mc)>0)
				@if (empty($mc->mc_end))
        		{{ Form::label('product', 'End: '.$mc->mc_end,['class'=>'control-label']) }}<br>
				@endif
        		{{ Form::label('product', 'Serial Number: '.$mc->mc_identification,['class'=>'control-label']) }}
				@else
				-
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
			No discharge order.
			</div>
		@endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/ward_discharges" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
            {{ Form::hidden('encounter_id', $ward_discharge->encounter_id)  }}
