@extends('layouts.app')

@section('content')
<h1>
New Stock Input Batch
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_input_batch, ['url'=>'stock_input_batches', 'class'=>'form-horizontal']) }} 
    
	@include('stock_input_batches.stock_input_batch')
{{ Form::close() }}

@endsection
