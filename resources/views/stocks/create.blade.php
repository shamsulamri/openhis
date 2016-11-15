@extends('layouts.app')

@section('content')
@include('products.id')
<h1>New Stock Movement</h1>

<br>
{{ Form::model($stock, ['url'=>'stocks', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
