@extends('layouts.app')

@section('content')
<h1>
Edit Stock
</h1>
@include('common.errors')
<br>
{{ Form::model($stock, ['route'=>['stocks.update',$stock->stock_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
