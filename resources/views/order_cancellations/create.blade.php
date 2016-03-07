@extends('layouts.app')

@include('patients.label')
@include('consultations.panel')
@section('content')
<h1>
New Order Cancellation
</h1>
@include('common.errors')
<br>
{{ Form::model($order_cancellation, ['url'=>'order_cancellations', 'class'=>'form-horizontal']) }} 
    
	@include('order_cancellations.order_cancellation')
{{ Form::close() }}

@endsection
