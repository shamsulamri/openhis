@extends('layouts.app')

@section('content')
<h1>Supplier List<a href='/suppliers/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a></h1>
<form action='/supplier/search' method='post'>
	<div class='input-group'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
