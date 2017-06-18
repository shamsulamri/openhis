@extends('layouts.app')

@section('content')
<h1>
Edit Stock Input
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_input, ['route'=>['stock_inputs.update',$stock_input->input_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stock_inputs.stock_input')
{{ Form::close() }}

@endsection
