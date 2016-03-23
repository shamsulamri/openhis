@extends('layouts.app')

@section('content')
@include('patients.label')
@include('common.errors')

{{ Form::model($discharge, ['url'=>'discharges', 'class'=>'form-horizontal']) }} 
	@include('discharges.discharge')
{{ Form::close() }}

@endsection
