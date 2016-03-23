@extends('layouts.app')

@section('content')
@include('patients.label')
@include('common.errors')

{{ Form::model($discharge, ['route'=>['discharges.update',$discharge->discharge_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('discharges.discharge')
{{ Form::close() }}

@endsection
