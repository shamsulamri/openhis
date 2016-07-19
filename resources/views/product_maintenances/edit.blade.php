@extends('layouts.app')

@section('content')
@include('products.id')
<h1>
Edit Product Maintenance
</h1>
@include('common.errors')
<br>
{{ Form::model($product_maintenance, ['route'=>['product_maintenances.update',$product_maintenance->maintain_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('product_maintenances.product_maintenance')
{{ Form::close() }}

@endsection
