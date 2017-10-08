@extends('layouts.app')

@section('content')
<h1>
New Order Route
</h1>
@include('common.errors')
<br>
{{ Form::model($order_route, ['url'=>'order_routes', 'class'=>'form-horizontal']) }} 
    
	@include('order_routes.order_route')
{{ Form::close() }}

@endsection
