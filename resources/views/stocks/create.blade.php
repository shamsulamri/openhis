@extends('layouts.app')

@section('content')
@include('products.id')
<h2>
New Stock Movement
</h2>
@include('common.errors')
<br>
{{ Form::model($stock, ['url'=>'stocks', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
