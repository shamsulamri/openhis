@extends('layouts.app')

@section('content')
<h1>
{{ $patient->patient_name }}
</h1>
@include('common.errors')
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="?tab=demography">Demography</a></li>
  <li role="presentation"><a href="?tab=contact">Contact</a></li>
  <li role="presentation"><a href="?tab=other">Other</a></li>
</ul>
<br>
{{ Form::model($patient, ['route'=>['patients.update',$patient->patient_id, 'tab=demography'],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('patients.patient')
{{ Form::close() }}

@endsection
