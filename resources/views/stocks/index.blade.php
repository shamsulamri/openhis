@extends('layouts.app')

@section('content')
<h1>Stock Index</h1>
<br>
<form action='/stock/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/stocks/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($stocks->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>product_code</th>
    <th>stock_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($stocks as $stock)
	<tr>
			<td>
					<a href='{{ URL::to('stocks/'. $stock->stock_id . '/edit') }}'>
						{{$stock->product_code}}
					</a>
			</td>
			<td>
					{{$stock->stock_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stocks/delete/'. $stock->stock_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stocks->appends(['search'=>$search])->render() }}
	@else
	{{ $stocks->render() }}
@endif
<br>
@if ($stocks->total()>0)
	{{ $stocks->total() }} records found.
@else
	No record found.
@endif
@endsection
