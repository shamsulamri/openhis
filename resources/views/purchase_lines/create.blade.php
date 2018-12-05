@extends('layouts.app')

@section('content')
<h1>
New Purchase Line
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_line, ['url'=>'purchase_lines', 'class'=>'form-horizontal']) }} 
    
	@include('purchase_lines.purchase_line')
{{ Form::close() }}

@endsection
