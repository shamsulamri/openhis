@extends('layouts.app')

@section('content')
<h1>
Delete Inventory Batch
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $inventory_batch->batch_number }}
{{ Form::open(['url'=>'inventory_batches/'.$inventory_batch->batch_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/inventory_batches" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
