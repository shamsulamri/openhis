@extends('layouts.app')

@section('content')
<h1>
New Order
</h1>
@include('common.errors')
<br>
{{ Form::model($order, ['url'=>'orders', 'class'=>'form-horizontal']) }} 
    
	@include('orders.order')
{{ Form::close() }}

@endsection
