@extends('layouts.app')

@section('content')
<h1>Supplier List</h1>
<br>
<form action='/supplier/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/suppliers/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($suppliers->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($suppliers as $supplier)
	<tr>
			<td>
					<a href='{{ URL::to('suppliers/'. $supplier->supplier_code . '/edit') }}'>
						{{$supplier->supplier_name}}
					</a>
			</td>
			<td>
					{{$supplier->supplier_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('suppliers/delete/'. $supplier->supplier_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $suppliers->appends(['search'=>$search])->render() }}
	@else
	{{ $suppliers->render() }}
@endif
<br>
@if ($suppliers->total()>0)
	{{ $suppliers->total() }} records found.
@else
	No record found.
@endif
@endsection
