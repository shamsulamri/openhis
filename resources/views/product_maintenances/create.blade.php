@extends('layouts.app')

@section('content')
@include('products.id')
<h1>
New Product Maintenance
</h1>

<br>
{{ Form::model($product_maintenance, ['url'=>'product_maintenances', 'class'=>'form-horizontal']) }} 
    
	@include('product_maintenances.product_maintenance')
{{ Form::close() }}

@endsection
