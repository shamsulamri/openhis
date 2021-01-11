@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>Log Arrival</h1>
<br>

{{ Form::model($ward_arrival, ['url'=>'ward_arrivals', 'class'=>'form-horizontal','onsubmit'=>'submitButton.disabled = true; return true;']) }} 
	@include('ward_arrivals.ward_arrival')
{{ Form::close() }}

@endsection
