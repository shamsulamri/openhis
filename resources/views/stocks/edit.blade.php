@extends('layouts.app')

@section('content')
@include('products.id')
<h2>
Edit Stock Movement
</h2>
@include('common.errors')
<br>
{{ Form::model($stock, ['route'=>['stocks.update',$stock->stock_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
