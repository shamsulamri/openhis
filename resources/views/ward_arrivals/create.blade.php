@extends('layouts.app')

@section('content')
<h1>
New Ward Arrival
</h1>
@include('common.errors')
<br>
{{ Form::model($ward_arrival, ['url'=>'ward_arrivals', 'class'=>'form-horizontal']) }} 
    
	@include('ward_arrivals.ward_arrival')
{{ Form::close() }}

@endsection
