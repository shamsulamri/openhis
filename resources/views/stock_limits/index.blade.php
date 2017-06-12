@extends('layouts.app')

@section('content')
<h1>Stock Limit Index
<a href='/stock_limits/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/stock_limit/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($stock_limits->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>limit_id</th>
    <th>limit_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($stock_limits as $stock_limit)
	<tr>
			<td>
					<a href='{{ URL::to('stock_limits/'. $stock_limit->limit_id . '/edit') }}'>
						{{$stock_limit->limit_id}}
					</a>
			</td>
			<td>
					{{$stock_limit->limit_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_limits/delete/'. $stock_limit->limit_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stock_limits->appends(['search'=>$search])->render() }}
	@else
	{{ $stock_limits->render() }}
@endif
<br>
@if ($stock_limits->total()>0)
	{{ $stock_limits->total() }} records found.
@else
	No record found.
@endif
@endsection
