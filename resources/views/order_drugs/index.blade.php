@extends('layouts.app')

@section('content')
<h1>Order Drug List</h1>
<br>
<form action='/order_drug/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/order_drugs/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($order_drugs->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>drug_strength</th>
    <th>id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_drugs as $order_drug)
	<tr>
			<td>
					<a href='{{ URL::to('order_drugs/'. $order_drug->id . '/edit') }}'>
						{{$order_drug->drug_strength}}
					</a>
			</td>
			<td>
					{{$order_drug->id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_drugs/delete/'. $order_drug->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_drugs->appends(['search'=>$search])->render() }}
	@else
	{{ $order_drugs->render() }}
@endif
<br>
@if ($order_drugs->total()>0)
	{{ $order_drugs->total() }} records found.
@else
	No record found.
@endif
@endsection
