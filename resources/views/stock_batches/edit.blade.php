@extends('layouts.app')

@section('content')
<h1>
Edit Stock Batch
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_batch, ['route'=>['stock_batches.update',$stock_batch->batch_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('stock_batches.stock_batch')
{{ Form::close() }}

@endsection
