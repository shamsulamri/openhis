@extends('layouts.app')

@section('content')
@include('patients.id')
<h2>
Ward Arrival
</h2>
@include('common.errors')
<br>
{{ Form::model($ward_arrival, ['url'=>'ward_arrivals', 'class'=>'form-horizontal']) }} 
    
	@include('ward_arrivals.ward_arrival')
{{ Form::close() }}

@endsection
