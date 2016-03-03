@extends('layouts.app')

@section('content')
<h1>
Edit Consultation Order
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_order, ['route'=>['consultation_orders.update',$consultation_order->product_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('product_code', $consultation_order->product_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('consultation_orders.consultation_order')
{{ Form::close() }}

@endsection
