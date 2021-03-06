@extends('layouts.app')

@section('content')
@if ($consultation)
@include('consultations.panel')
@else
@include('patients.id_only')
@endif
<h1>
Stop Order
</h1>
<h2>
Are you sure you want to stop this order ?
</h2>
@include('common.errors')
<br>
{{ Form::model($order_stop, ['url'=>'order_stops', 'class'=>'form-horizontal']) }} 
    
	@include('order_stops.order_stop')
{{ Form::close() }}

@endsection
