@extends('layouts.app')

@section('content')
<h1><a href='/products'>Product List</a> / <a href='/stocks/{{ $product->product_code }}'>Stock Movements</a> / New</h1>
<br>
@include('products.id')
@include('common.errors')
<br>
{{ Form::model($stock, ['url'=>'stocks', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
