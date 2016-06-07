@extends('layouts.app2')

@section('content')
@if (Auth::user()->authorization->module_consultation==1)

@else
		@include('patients.id')
@endif
@include('common.errors')

{{ Form::model($order, ['url'=>'orders', 'class'=>'form-horizontal']) }} 
	@include('orders.order_show')
{{ Form::close() }}

@endsection
