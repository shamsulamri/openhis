@extends('layouts.app')

@section('content')
@include('orders.panel')


{{ Form::model($order_drug, ['url'=>'order_drugs', 'class'=>'form-horizontal']) }} 
	@include('order_drugs.order_drug')
{{ Form::close() }}

@endsection
