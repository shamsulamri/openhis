@extends('layouts.app2')

@section('content')
@include('common.errors')

{{ Form::model($order_investigation, ['url'=>'order_investigations', 'class'=>'form-horizontal']) }} 
	@include('order_investigations.order_investigation')
{{ Form::close() }}

@endsection
