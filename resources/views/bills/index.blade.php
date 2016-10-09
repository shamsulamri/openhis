@extends('layouts.app')

@section('content')
<h1>Bill Index</h1>
<br>
<form action='/bill/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/bills/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($bills->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Id</th> 
    <th>Grand Total</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bills as $bill)
	<tr>
			<td>
					{{$bill->id}}
			</td>
			<td>
					<a href='{{ URL::to('bills/'. $bill->id . '/edit') }}'>
						{{$bill->bill_grand_total}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bills/delete/'. $bill->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bills->appends(['search'=>$search])->render() }}
	@else
	{{ $bills->render() }}
@endif
<br>
@if ($bills->total()>0)
	{{ $bills->total() }} records found.
@else
	No record found.
@endif
@endsection
