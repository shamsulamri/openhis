@extends('layouts.app')

@section('content')
<h1>Stock Receive Index
<a href='/stock_receives/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/stock_receive/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($stock_receives->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>purchase_id</th>
    <th>receive_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($stock_receives as $stock_receive)
	<tr>
			<td>
					<a href='{{ URL::to('stock_receives/'. $stock_receive->receive_id . '/edit') }}'>
						{{$stock_receive->purchase_id}}
					</a>
			</td>
			<td>
					{{$stock_receive->receive_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_receives/delete/'. $stock_receive->receive_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stock_receives->appends(['search'=>$search])->render() }}
	@else
	{{ $stock_receives->render() }}
@endif
<br>
@if ($stock_receives->total()>0)
	{{ $stock_receives->total() }} records found.
@else
	No record found.
@endif
@endsection