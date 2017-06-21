@extends('layouts.app')

@section('content')
<h1>
New Stock Input Line
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_input_line, ['url'=>'stock_input_lines', 'class'=>'form-horizontal']) }} 
    
	@include('stock_input_lines.stock_input_line')
{{ Form::close() }}

@endsection
