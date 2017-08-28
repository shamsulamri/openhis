@extends('layouts.app')

@section('content')
<h1>
New Stock Receive
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_receive, ['url'=>'stock_receives', 'class'=>'form-horizontal']) }} 
    
	@include('stock_receives.stock_receive')
{{ Form::close() }}

@endsection
