@extends('layouts.app')

@section('content')
<h1>
New Stock
</h1>
@include('common.errors')
<br>
{{ Form::model($stock, ['url'=>'stocks', 'class'=>'form-horizontal']) }} 
    
	@include('stocks.stock')
{{ Form::close() }}

@endsection
