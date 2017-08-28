@extends('layouts.app')

@section('content')
<h1>
Edit Stock Receive
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_receive, ['route'=>['stock_receives.update',$stock_receive->receive_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stock_receives.stock_receive')
{{ Form::close() }}

@endsection
