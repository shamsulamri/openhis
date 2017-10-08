@extends('layouts.app')

@section('content')
<h1>
Edit Order Route
</h1>
@include('common.errors')
<br>
{{ Form::model($order_route, ['route'=>['order_routes.update',$order_route->route_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_routes.order_route')
{{ Form::close() }}

@endsection
