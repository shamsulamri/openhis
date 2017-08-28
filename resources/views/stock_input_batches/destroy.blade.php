@extends('layouts.app2')

@section('content')
<h3>
Delete Stock Input Batch
</h3>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $stock_input_batch->batch_number }}
{{ Form::open(['url'=>'stock_input_batches/'.$stock_input_batch->batch_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/stock_input_batches/batch/{{ $stock_input_batch->line_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
