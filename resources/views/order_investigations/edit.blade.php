@extends('layouts.app')

@section('content')
<h1>
Edit Order Investigation
</h1>
@include('common.errors')
<br>
{{ Form::model($order_investigation, ['route'=>['order_investigations.update',$order_investigation->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_investigations.order_investigation')
{{ Form::close() }}

@endsection
