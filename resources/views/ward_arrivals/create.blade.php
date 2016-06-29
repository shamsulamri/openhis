@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Ward Arrival
</h1>
@include('common.errors')

<br>
<h4>Log patient arrival time.</h4>
<br>
{{ Form::model($ward_arrival, ['url'=>'ward_arrivals', 'class'=>'form-horizontal']) }} 
    
	@include('ward_arrivals.ward_arrival')
{{ Form::close() }}

@endsection
