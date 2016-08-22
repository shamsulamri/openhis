@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Obstetric History</h1>
<br>
@include('common.notification')
@include('common.errors')
{{ Form::model($patient, ['url'=>'obstetric', 'class'=>'form-horizontal']) }} 

	<div class='form-group  @if ($errors->has('patient_gravida')) has-error @endif'>
        <label for='patient_gravida' class='col-sm-2 control-label'>Gravida</label>
        <div class='col-sm-10'>
            {{ Form::text('patient_gravida', null, ['class'=>'form-control']) }}
            @if ($errors->has('patient_gravida')) <p class="help-block">{{ $errors->first('patient_gravida') }}</p> @endif
        </div>
    </div>


	<div class="row">
			<div class="col-xs-4">
					<div class='form-group  @if ($errors->has('patient_parity')) has-error @endif'>
						<label for='patient_parity' class='col-sm-6 control-label'>Parity</label>
						<div class='col-sm-6'>
							{{ Form::text('patient_parity', null, ['class'=>'form-control']) }}
							@if ($errors->has('patient_parity')) <p class="help-block">{{ $errors->first('patient_parity') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group  @if ($errors->has('patient_parity_plus')) has-error @endif'>
						<label for='patient_parity_plus' class='col-sm-2 control-label'>+</label>
						<div class='col-sm-6'>
							{{ Form::text('patient_parity_plus', null, ['class'=>'form-control']) }}
							@if ($errors->has('patient_parity_plus')) <p class="help-block">{{ $errors->first('patient_parity_plus') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class='form-group  @if ($errors->has('patient_lnmp')) has-error @endif'>
        <label for='patient_lnmp' class='col-sm-2 control-label'>LNMP</label>
        <div class='col-sm-10'>
			<input id="patient_lnmp" name="patient_lnmp" type="text">
            @if ($errors->has('patient_lnmp')) <p class="help-block">{{ $errors->first('patient_lnmp') }}</p> @endif
        </div>
    </div>
	<br>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

	<script>
		$(function(){
				$('#patient_lnmp').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $patient->patient_lnmp }}',
						maxYear: 2016,
						minYear: 1900,
						customClass: 'select'
				});    
		});

	</script>
@endsection
