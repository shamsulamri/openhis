@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Medication Administration Record
</h1>
@include('common.errors')
<br>
{{ Form::model($medication_record, ['route'=>['medication_records.update',$medication_record->medication_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	<div class='form-group  @if ($errors->has('medication_datetime')) has-error @endif'>
		<label for='order' class='col-sm-2 control-label'>Drug</label>
		<div class='col-sm-10'>
			{{ Form::label('drug', $order->product->product_name, ['class'=>'form-control']) }}
		</div>
	</div>
    <div class='form-group  @if ($errors->has('medication_datetime')) has-error @endif'>
        <label for='medication_datetime' class='col-sm-2 control-label'>Record</label>
        <div class='col-sm-10'>
            {{ Form::label('medication_datetime', DojoUtility::dateTimeReadFormat($medication_record->medication_datetime), ['class'=>'form-control']) }}
            @if ($errors->has('medication_datetime')) <p class="help-block">{{ $errors->first('medication_datetime') }}</p> @endif
        </div>
    </div>

	<hr>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('medication_date')) has-error @endif'>
						{{ Form::label('Date', 'Date',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="medication_date" id="medication_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($medication_record->medication_datetime) }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('medication_time')) has-error @endif'>
						{{ Form::label('Time', 'Time',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
								<div id="medication_time" name="medication_time" class="input-group clockpicker" data-autoclose="true">
										{{ Form::text('medication_time', DojoUtility::timeReadFormat($medication_record->medication_datetime), ['class'=>'form-control','data-mask'=>'99:99']) }}
										<span class="input-group-addon">
											<span class="fa fa-clock-o"></span>
										</span>
								</div>

						</div>
					</div>
			</div>
	</div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/medication_record/mar/{{ $order->encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

<script>
		$('.clockpicker').clockpicker();

		$('#medication_date').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
		});
</script>
{{ Form::close() }}

@endsection
