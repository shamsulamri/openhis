@extends('layouts.app2')

@section('content')
@cannot('module-order')
		@include('patients.id')
@endcan
{{ Form::model($order, ['url'=>'orders', 'class'=>'form-horizontal']) }} 
	@include('orders.order_show')
{{ Form::close() }}

@endsection
