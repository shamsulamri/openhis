@extends('layouts.app')

@section('content')

@if ($is_drug)
		@if ($consultation)
		@include('consultations.panel')
		@else
		@include('patients.id_only')
		@endif
@endif
<h1>Order Cancellation</h1>
<br>


{{ Form::model($order_cancellation, ['url'=>'order_cancellations', 'class'=>'form-horizontal']) }} 
	@include('order_cancellations.order_cancellation')
{{ Form::close() }}

@endsection
