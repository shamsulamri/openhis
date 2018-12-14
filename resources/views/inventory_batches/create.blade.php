@extends('layouts.app')

@section('content')
<h1>
New Inventory Batch
</h1>
@include('common.errors')
<br>
{{ Form::model($inventory_batch, ['url'=>'inventory_batches', 'class'=>'form-horizontal']) }} 
    
	@include('inventory_batches.inventory_batch')
{{ Form::close() }}

@endsection
