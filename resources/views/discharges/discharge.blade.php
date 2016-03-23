<h2>Discharge</h2>
<br>
	<div class='form-group  @if ($errors->has('discharge_date')) has-error @endif'>
        {{ Form::label('discharge_date', 'Date',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('discharge_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('discharge_date')) <p class="help-block">{{ $errors->first('discharge_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('type_code')) has-error @endif'>
        <label for='type_code' class='col-sm-2 control-label'>Discharge Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('type_code', $type,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('type_code')) <p class="help-block">{{ $errors->first('type_code') }}</p> @endif
        </div>
    </div>
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
            <a class="btn btn-default" href="/consultations/{{ $consultation->consultation_id }}/edit" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>


	{{ Form::hidden('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
	{{ Form::hidden('consultation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
	{{ Form::hidden('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
