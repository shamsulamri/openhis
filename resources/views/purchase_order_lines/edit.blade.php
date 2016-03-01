@extends('layouts.app')

@section('content')
<h1>
Edit Purchase Order Line
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_order_line, ['route'=>['purchase_order_lines.update',$purchase_order_line->line_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('purchase_order_lines.purchase_order_line')
{{ Form::close() }}

@endsection
