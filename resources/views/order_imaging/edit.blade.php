@extends('layouts.app')

@section('content')
<h1>
Edit Order Imaging
</h1>
@include('common.errors')
<br>
{{ Form::model($order_imaging, ['route'=>['order_imaging.update',$order_imaging->product_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('product_code', $order_imaging->product_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('order_imaging.order_imaging')
{{ Form::close() }}

@endsection
