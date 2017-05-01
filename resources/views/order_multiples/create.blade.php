@extends('layouts.app')

@section('content')
<h1>
New Order Multiple
</h1>
@include('common.errors')
<br>
{{ Form::model($order_multiple, ['url'=>'order_multiples', 'class'=>'form-horizontal']) }} 
    
	@include('order_multiples.order_multiple')
{{ Form::close() }}

@endsection
