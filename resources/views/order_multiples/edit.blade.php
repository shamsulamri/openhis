@extends('layouts.app')

@section('content')
<h1>
Edit Order Multiple
</h1>
@include('common.errors')
<br>
{{ Form::model($order_multiple, ['route'=>['order_multiples.update',$order_multiple->multiple_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_multiples.order_multiple')
{{ Form::close() }}

@endsection
