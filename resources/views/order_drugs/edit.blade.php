@extends('layouts.app')

@section('content')
<h1>
Edit Order Drug
</h1>
@include('common.errors')
<br>
{{ Form::model($order_drug, ['route'=>['order_drugs.update',$order_drug->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('order_drugs.order_drug')
{{ Form::close() }}

@endsection
