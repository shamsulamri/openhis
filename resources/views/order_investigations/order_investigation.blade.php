    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-2 control-label'>Product</label>
        <div class='col-sm-10'>
            {{ Form::label('product_code', $product[0]->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('investigation_date')) has-error @endif'>
        <label for='investigation_date' class='col-sm-2 control-label'>Date Start<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('investigation_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('investigation_date')) <p class="help-block">{{ $errors->first('investigation_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_description')) has-error @endif'>
        {{ Form::label('Description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('order_description', $order->order_description, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('order_description')) <p class="help-block">{{ $errors->first('order_description') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('order_quantity_request')) has-error @endif'>
        <label for='order_quantity_request' class='col-sm-2 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_quantity_request', $order->order_quantity_request, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_request')) <p class="help-block">{{ $errors->first('order_quantity_request') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('urgency_code')) has-error @endif'>
        {{ Form::label('urgency_code', 'Urgency',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('urgency_code', $urgency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('urgency_code')) <p class="help-block">{{ $errors->first('urgency_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('investigation_recur')) has-error @endif'>
        {{ Form::label('investigation_recur', 'Recurs',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('investigation_recur', '1') }}
            @if ($errors->has('investigation_recur')) <p class="help-block">{{ $errors->first('investigation_recur') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('investigation_duration')) has-error @endif'>
						{{ Form::label('investigation_duration', 'Duration',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('investigation_duration', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('investigation_duration')) <p class="help-block">{{ $errors->first('investigation_duration') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						<div class='col-md-12'>
							{{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'10']) }}
							@if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

    <div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
        {{ Form::label('frequency_code', 'Frequency',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('frequency_code', $frequency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_is_discharge')) has-error @endif'>
        {{ Form::label('Discharge Order', 'Discharge Order',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('order_is_discharge', '1', $order->order_is_discharge) }}
            @if ($errors->has('order_is_discharge')) <p class="help-block">{{ $errors->first('order_is_discharge') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/orders/{{ $consultation->consultation_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

    {{ Form::hidden('consultation_id', $consultation->consultation_id, ['class'=>'form-control','placeholder'=>'',]) }}
    {{ Form::hidden('product_code', $product[0]->product_code, ['class'=>'form-control','placeholder'=>'',]) }}
