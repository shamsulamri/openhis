@extends('layouts.app')

@section('content')
<h1>
Edit Inventory Batch
</h1>
@include('common.errors')
<br>
{{ Form::model($inventory_batch, ['route'=>['inventory_batches.update',$inventory_batch->batch_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('inventory_batches.inventory_batch')
{{ Form::close() }}

@endsection
