@extends('layouts.app2')

@section('content')
<h3>
{{ $product->product_name }}
<br>
<small>{{ $product->product_code }}</small>
</h3>
@include('common.errors')
<br>
{{ Form::model($stock_input_line, ['route'=>['stock_input_lines.update',$stock_input_line->line_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stock_input_lines.stock_input_line')
{{ Form::close() }}

@endsection
