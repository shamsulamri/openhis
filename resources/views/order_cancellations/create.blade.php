@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($order_cancellation, ['url'=>'order_cancellations', 'class'=>'form-horizontal']) }} 
	@include('order_cancellations.order_cancellation')
{{ Form::close() }}

@endsection
