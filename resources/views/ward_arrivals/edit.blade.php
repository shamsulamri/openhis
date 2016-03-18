@extends('layouts.app')

@section('content')
<h1>
Edit Ward Arrival
</h1>
@include('common.errors')
<br>
{{ Form::model($ward_arrival, ['route'=>['ward_arrivals.update',$ward_arrival->arrival_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('ward_arrivals.ward_arrival')
{{ Form::close() }}

@endsection
