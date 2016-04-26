@extends('layouts.app2')

@section('content')
<h3>
Edit Line 
</h3>
@include('common.errors')
<br>
{{ Form::model($purchase_order_line, ['route'=>['purchase_order_lines.update',$purchase_order_line->line_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('purchase_order_lines.purchase_order_line')
{{ Form::close() }}

@endsection
