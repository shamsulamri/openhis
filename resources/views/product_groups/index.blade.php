@extends('layouts.app')

@section('content')
<h1>Product Group List
<a href='/product_groups/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/product_group/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($product_groups->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_groups as $product_group)
	<tr>
			<td>
					<a href='{{ URL::to('product_groups/'. $product_group->group_code . '/edit') }}'>
						{{$product_group->group_name}}
					</a>
			</td>
			<td>
					{{$product_group->group_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_groups/delete/'. $product_group->group_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_groups->appends(['search'=>$search])->render() }}
	@else
	{{ $product_groups->render() }}
@endif
<br>
@if ($product_groups->total()>0)
	{{ $product_groups->total() }} records found.
@else
	No record found.
@endif
@endsection
