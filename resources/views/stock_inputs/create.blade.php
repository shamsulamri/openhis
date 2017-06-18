@extends('layouts.app')

@section('content')
<h1>
New Stock Input
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_input, ['url'=>'stock_inputs', 'class'=>'form-horizontal']) }} 
    
	@include('stock_inputs.stock_input')
{{ Form::close() }}

@endsection
