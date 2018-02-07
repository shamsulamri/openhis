@extends('layouts.app')

@section('content')
<h1>
Edit Order Stop
</h1>
@include('common.errors')
<br>
{{ Form::model($order_stop, ['route'=>['order_stops.update',$order_stop->stop_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_stops.order_stop')
{{ Form::close() }}

@endsection
