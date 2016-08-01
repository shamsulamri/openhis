@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')
<ul class="nav nav-tabs">
  <li role="presentation"><a href="?tab=demography">Demography</a></li>
  <li role="presentation"><a href="?tab=contact">Contact</a></li>
  <li role="presentation" class="active"><a href="?tab=other">Other</a></li>
</ul>
<br>
{{ Form::model($patient, ['route'=>['patients.update',$patient->patient_id,'tab=contact'],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    <div class='form-group  @if ($errors->has('patient_is_pati')) has-error @endif'>
        {{ Form::label('Illegal Immigrant', 'Illegal Immigrant',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('patient_is_pati', '1') }}
            @if ($errors->has('patient_is_pati')) <p class="help-block">{{ $errors->first('patient_is_pati') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_is_royal')) has-error @endif'>
        {{ Form::label('Royalty', 'Royalty',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('patient_is_royal', '1') }}
            @if ($errors->has('patient_is_royal')) <p class="help-block">{{ $errors->first('patient_is_royal') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('patient_is_vip')) has-error @endif'>
        {{ Form::label('VIP', 'VIP',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('patient_is_vip', '1') }}
            @if ($errors->has('patient_is_vip')) <p class="help-block">{{ $errors->first('patient_is_vip') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('patient_related_mrn')) has-error @endif'>
        {{ Form::label('Related MRN', 'Related MRN',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_related_mrn', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_related_mrn')) <p class="help-block">{{ $errors->first('patient_related_mrn') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('relation_code')) has-error @endif'>
        {{ Form::label('Relationship', 'Relationship',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('relation_code', $relationship,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('relation_code')) <p class="help-block">{{ $errors->first('relation_code') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('patient_gravida')) has-error @endif'>
        {{ Form::label('Gravida', 'Gravida',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_gravida', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_gravida')) <p class="help-block">{{ $errors->first('patient_gravida') }}</p> @endif
        </div>
    </div>


	<div class='form-group  @if ($errors->has('patient_parity')) has-error @endif'>
        {{ Form::label('Parity', 'Parity',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_parity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_parity')) <p class="help-block">{{ $errors->first('patient_parity') }}</p> @endif
        </div>
    </div>


	<div class='form-group  @if ($errors->has('patient_parity_plus')) has-error @endif'>
        {{ Form::label('Parity Plus', 'Parity Plus',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_parity_plus', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_parity_plus')) <p class="help-block">{{ $errors->first('patient_parity_plus') }}</p> @endif
        </div>
    </div>

	<div class='form-group  @if ($errors->has('patient_lnmp')) has-error @endif'>
        {{ Form::label('Last Normal Menstrul Date', 'Last Normal Menstrul Date',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('patient_lnmp', null, ['class'=>'form-control','placeholder'=>'dd/mm/yyyy',]) }}
            @if ($errors->has('patient_lnmp')) <p class="help-block">{{ $errors->first('patient_lnmp') }}</p> @endif
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

