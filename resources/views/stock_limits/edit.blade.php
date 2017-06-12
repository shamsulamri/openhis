@extends('layouts.app')

@section('content')
<h1>
Edit Stock Limit
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_limit, ['route'=>['stock_limits.update',$stock_limit->limit_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stock_limits.stock_limit')
{{ Form::close() }}

@endsection
