@extends('layouts.app')

@section('content')
<h1>
New Order
</h1>
@include('common.errors')
<br>
{{ Form::model($order, ['url'=>'order_maintenances', 'class'=>'form-horizontal']) }} 
    
	@include('order_maintenances.order')
{{ Form::close() }}

@endsection
