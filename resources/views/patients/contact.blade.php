@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')
<ul class="nav nav-tabs">
  <li role="presentation"><a href="?tab=demography">Demography</a></li>
  <li role="presentation" class="active"><a href="?tab=contact">Contact</a></li>
  <li role="presentation"><a href="?tab=other">Other</a></li>
</ul>
<br>
{{ Form::model($patient, ['route'=>['patients.update',$patient->patient_id,'tab=contact'],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
<strong>Current Address</strong>
<br>
<br>

    <div class='form-group  @if ($errors->has('patient_cur_street_1')) has-error @endif'>
        {{ Form::label('Street 1', 'Street 1',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_cur_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_cur_street_1')) <p class="help-block">{{ $errors->first('patient_cur_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_cur_street_2')) has-error @endif'>
        {{ Form::label('Street 2', 'Street 2',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_cur_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_cur_street_2')) <p class="help-block">{{ $errors->first('patient_cur_street_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_cur_street_3')) has-error @endif'>
        {{ Form::label('Street 3', 'Street 3',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_cur_street_3', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_cur_street_3')) <p class="help-block">{{ $errors->first('patient_cur_street_3') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
				<div class='form-group  @if ($errors->has('patient_cur_city')) has-error @endif'>
						{{ Form::label('City', 'City',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_cur_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
							@if ($errors->has('patient_cur_city')) <p class="help-block">{{ $errors->first('patient_cur_city') }}</p> @endif
						</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class='form-group  @if ($errors->has('patient_cur_postcode')) has-error @endif'>
						{{ Form::label('Postcode', 'Postcode',['class'=>'col-md-4 control-label']) }}
						<div class='col-md-8'>
							{{ Form::text('patient_cur_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'5']) }}
							@if ($errors->has('patient_cur_postcode')) <p class="help-block">{{ $errors->first('patient_cur_postcode') }}</p> @endif
						</div>
				</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_cur_state')) has-error @endif'>
        {{ Form::label('State', 'State',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::select('patient_cur_state', $state, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('patient_cur_state')) <p class="help-block">{{ $errors->first('patient_cur_state') }}</p> @endif
        </div>
    </div>


			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_cur_country')) has-error @endif'>
        {{ Form::label('Country', 'Country',['class'=>'col-md-4 control-label']) }}
        
<div class='col-md-8'>

            {{ Form::select('patient_cur_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('patient_cur_country')) <p class="help-block">{{ $errors->first('patient_cur_country') }}</p> @endif
    	</div>
    </div>

			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
 <div class='form-group  @if ($errors->has('patient_phone_home')) has-error @endif'>
        {{ Form::label('Phone Home', 'Phone Home',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_phone_home', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_home')) <p class="help-block">{{ $errors->first('patient_phone_home') }}</p> @endif
        </div>
    </div>
			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_phone_mobile')) has-error @endif'>
        {{ Form::label('Phone Mobile', 'Phone Mobile',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_phone_mobile', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_mobile')) <p class="help-block">{{ $errors->first('patient_phone_mobile') }}</p> @endif
        </div>
    </div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_phone_office')) has-error @endif'>
        {{ Form::label('Phone Office', 'Phone Office',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_phone_office', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'15']) }}
            @if ($errors->has('patient_phone_office')) <p class="help-block">{{ $errors->first('patient_phone_office') }}</p> @endif
        </div>
    </div>


			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_phone_fax')) has-error @endif'>
        {{ Form::label('Phone Fax', 'Phone Fax',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_phone_fax', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('patient_phone_fax')) <p class="help-block">{{ $errors->first('patient_phone_fax') }}</p> @endif
        </div>
    </div>


			</div>
	</div>



   



    <div class='form-group  @if ($errors->has('patient_email')) has-error @endif'>
        {{ Form::label('Email', 'Email',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_email', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('patient_email')) <p class="help-block">{{ $errors->first('patient_email') }}</p> @endif
        </div>
    </div>
<strong>Permanent Address</strong>
<br>
<br>
    <div class='form-group  @if ($errors->has('patient_per_street_1')) has-error @endif'>
        {{ Form::label('Street 1', 'Street 1',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_per_street_1', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_per_street_1')) <p class="help-block">{{ $errors->first('patient_per_street_1') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_per_street_2')) has-error @endif'>
        {{ Form::label('Street 2', 'Street 2',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_per_street_2', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_per_street_2')) <p class="help-block">{{ $errors->first('patient_per_street_2') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_per_street_3')) has-error @endif'>
        {{ Form::label('Street 3', 'Street 3',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_per_street_3', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('patient_per_street_3')) <p class="help-block">{{ $errors->first('patient_per_street_3') }}</p> @endif
        </div>
    </div>
	

	<div class="row">
			<div class="col-xs-6">
 <div class='form-group  @if ($errors->has('patient_per_city')) has-error @endif'>
        {{ Form::label('City', 'City',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_per_city', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('patient_per_city')) <p class="help-block">{{ $errors->first('patient_per_city') }}</p> @endif
        </div>
    </div>
			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_per_postcode')) has-error @endif'>
        {{ Form::label('Postcode', 'Postcode',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::text('patient_per_postcode', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'5']) }}
            @if ($errors->has('patient_per_postcode')) <p class="help-block">{{ $errors->first('patient_per_postcode') }}</p> @endif
        </div>
    </div>
			</div>
	</div>
   
<div class="row">
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_per_state')) has-error @endif'>
        {{ Form::label('State', 'State',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
            {{ Form::select('patient_per_state', $state, null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('patient_per_state')) <p class="help-block">{{ $errors->first('patient_per_state') }}</p> @endif
        </div>
    </div>


			</div>
			<div class="col-xs-6">
    <div class='form-group  @if ($errors->has('patient_per_country')) has-error @endif'>
        {{ Form::label('Country', 'Country',['class'=>'col-md-4 control-label']) }}
        <div class='col-md-8'>
			{{ Form::select('patient_per_country', $nation,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('patient_per_country')) <p class="help-block">{{ $errors->first('patient_per_country') }}</p> @endif
        </div>
    </div>


			</div>
	</div>




	<div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/patients/{{ $patient->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection

