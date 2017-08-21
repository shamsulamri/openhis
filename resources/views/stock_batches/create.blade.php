@extends('layouts.app')

@section('content')
<h1>
New Stock Batch
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_batch, ['url'=>'stock_batches', 'class'=>'form-horizontal']) }} 
    
	@include('stock_batches.stock_batch')
{{ Form::close() }}

@endsection
