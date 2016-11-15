@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Discharge</h1>
<br>


{{ Form::model($discharge, ['url'=>'discharges', 'class'=>'form-horizontal']) }} 
	@include('discharges.discharge')
{{ Form::close() }}

@endsection
