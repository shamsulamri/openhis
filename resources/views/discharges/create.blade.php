@extends('layouts.app')

@section('content')
@include('patients.label')
@include('common.errors')

<h2>Discharge</h2>
<br>
{{ Form::model($discharge, ['url'=>'discharges', 'class'=>'form-horizontal']) }} 
	@include('discharges.discharge')
{{ Form::close() }}

@endsection
