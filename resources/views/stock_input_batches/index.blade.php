@extends('layouts.app')

@section('content')
<h1>Stock Input Batch Index
<a href='/stock_input_batches/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/stock_input_batch/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($stock_input_batches->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>batch_number</th>
    <th>batch_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($stock_input_batches as $stock_input_batch)
	<tr>
			<td>
					<a href='{{ URL::to('stock_input_batches/'. $stock_input_batch->batch_id . '/edit') }}'>
						{{$stock_input_batch->batch_number}}
					</a>
			</td>
			<td>
					{{$stock_input_batch->batch_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_input_batches/delete/'. $stock_input_batch->batch_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stock_input_batches->appends(['search'=>$search])->render() }}
	@else
	{{ $stock_input_batches->render() }}
@endif
<br>
@if ($stock_input_batches->total()>0)
	{{ $stock_input_batches->total() }} records found.
@else
	No record found.
@endif
@endsection
