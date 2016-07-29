@extends('layouts.app')

@section('content')
@include('products.id')
<h1>New Stock Movement</h1>
@include('common.errors')
<br>
{{ Form::model($stock, ['url'=>'stocks', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
