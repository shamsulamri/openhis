@extends('layouts.app')

@section('content')
<h1>
New Stock Limit
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_limit, ['url'=>'stock_limits', 'class'=>'form-horizontal']) }} 
    
	@include('stock_limits.stock_limit')
{{ Form::close() }}

@endsection
