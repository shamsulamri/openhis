@extends('layouts.app')

@section('content')
<h1>
New Order Drug
</h1>
@include('common.errors')
<br>
{{ Form::model($order_drug, ['url'=>'order_drugs', 'class'=>'form-horizontal']) }} 
    
	@include('order_drugs.order_drug')
{{ Form::close() }}

@endsection
