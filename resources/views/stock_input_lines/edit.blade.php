@extends('layouts.app')

@section('content')
<h1>
Edit Stock Input Line
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_input_line, ['route'=>['stock_input_lines.update',$stock_input_line->line_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stock_input_lines.stock_input_line')
{{ Form::close() }}

@endsection
