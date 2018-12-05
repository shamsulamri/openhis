@extends('layouts.app2')

@section('content')
<h3>
Edit Line 
</h3>
@include('common.errors')
<br>
{{ Form::model($purchase_line, ['route'=>['purchase_lines.update',$purchase_line->line_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('purchase_lines.purchase_line')
{{ Form::close() }}

@endsection
