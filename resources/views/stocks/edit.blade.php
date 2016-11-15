@extends('layouts.app')

@section('content')
@include('products.id')
<h1>
Edit Stock Movement
</h1>

<br>
{{ Form::model($stock, ['route'=>['stocks.update',$stock->stock_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
