@extends('layouts.app')

@section('content')
<h1>
New Stock Bulk Movement
</h1>
<br>
{{ Form::model($stock_input, ['url'=>'stock_inputs', 'class'=>'form-horizontal']) }} 
    
	@include('stock_inputs.stock_input')
{{ Form::close() }}

@endsection
