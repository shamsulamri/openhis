@extends('layouts.app')

@section('content')
<h1>
Edit Stock Input Batch
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_input_batch, ['route'=>['stock_input_batches.update',$stock_input_batch->batch_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stock_input_batches.stock_input_batch')
{{ Form::close() }}

@endsection
