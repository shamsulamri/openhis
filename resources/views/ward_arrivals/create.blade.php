@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Log Ward Arrival</h1>
<br>
@include('common.errors')

{{ Form::model($ward_arrival, ['url'=>'ward_arrivals', 'class'=>'form-horizontal']) }} 
    
	@include('ward_arrivals.ward_arrival')
{{ Form::close() }}

@endsection
