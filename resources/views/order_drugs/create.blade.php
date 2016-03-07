@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
<h3>
{{ $product->product_name }}
</h3>
@include('common.errors')
<br>
{{ Form::model($order_drug, ['url'=>'order_drugs', 'class'=>'form-horizontal']) }} 
    
	@include('order_drugs.order_drug')
{{ Form::close() }}

@endsection
