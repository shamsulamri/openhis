@extends('layouts.app')

@section('content')
@if (Auth::user()->authorization->author_consultation==1)
		@include('patients.label')
		@include('consultations.panel')		
@else
		@include('patients.id')
@endif
@include('common.errors')

{{ Form::model($order, ['url'=>'orders', 'class'=>'form-horizontal']) }} 
	@include('orders.order_show')
{{ Form::close() }}

@endsection
