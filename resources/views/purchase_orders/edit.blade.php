@extends('layouts.app2')

@section('content')

@if (count($errors) > 0)
    <!-- form error list -->
	<br>
    <div class="alert alert-danger">
			please correct the errors highlighted below.
    </div>
@endif
{{ Form::model($purchase_order, ['route'=>['purchase_orders.update',$purchase_order->purchase_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('purchase_orders.purchase_order')
{{ Form::close() }}

@endsection
