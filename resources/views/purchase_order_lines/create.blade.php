@extends('layouts.app')

@section('content')
<h1>
New Purchase Order Line
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_order_line, ['url'=>'purchase_order_lines', 'class'=>'form-horizontal']) }} 
    
	@include('purchase_order_lines.purchase_order_line')
{{ Form::close() }}

@endsection
