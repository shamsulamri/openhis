@extends('layouts.app2')

@section('content')
@cannot('module-consultation')
		@include('patients.id')
@endcan
{{ Form::model($order, ['url'=>'orders', 'class'=>'form-horizontal']) }} 
	@include('orders.order_show')
{{ Form::close() }}

@endsection
