@extends('layouts.app')

@section('content')
<h1>Tax Type Index
<a href='/tax_types/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/tax_type/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($tax_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($tax_types as $tax_type)
	<tr>
			<td>
					<a href='{{ URL::to('tax_types/'. $tax_type->type_code . '/edit') }}'>
						{{$tax_type->type_name}}
					</a>
			</td>
			<td>
					{{$tax_type->type_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('tax_types/delete/'. $tax_type->type_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $tax_types->appends(['search'=>$search])->render() }}
	@else
	{{ $tax_types->render() }}
@endif
<br>
@if ($tax_types->total()>0)
	{{ $tax_types->total() }} records found.
@else
	No record found.
@endif
@endsection
