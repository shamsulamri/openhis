@extends('layouts.app')

@section('content')
<h1>
Edit Order
</h1>
@include('common.errors')
<br>
{{ Form::model($order, ['route'=>['order_maintenances.update',$order->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
  	  
    <div class='form-group @if ($errors->has('order_id')) has-error @endif'>
        <label for='order_id' class='col-sm-2 control-label'>Order Id<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('order_id', $order->order_id, ['class'=>'control-label']) }}
        </div>
    </div>
	@include('order_maintenances.order')
{{ Form::close() }}

@endsection
