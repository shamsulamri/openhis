@extends('layouts.app')

@section('content')
<h1>
New Order Investigation
</h1>
@include('common.errors')
<br>
{{ Form::model($order_investigation, ['url'=>'order_investigations', 'class'=>'form-horizontal']) }} 
    
	@include('order_investigations.order_investigation')
{{ Form::close() }}

@endsection
