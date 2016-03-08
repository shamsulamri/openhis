@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($order_cancellation, ['route'=>['order_cancellations.update',$order_cancellation->cancel_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_cancellations.order_cancellation')
{{ Form::close() }}

@endsection
