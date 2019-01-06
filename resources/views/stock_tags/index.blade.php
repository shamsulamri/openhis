@extends('layouts.app')

@section('content')
<h1>Stock Tag Index
<a href='/stock_tags/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/stock_tag/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($stock_tags->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>tag_name</th>
    <th>tag_code</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($stock_tags as $stock_tag)
	<tr>
			<td>
					<a href='{{ URL::to('stock_tags/'. $stock_tag->tag_code . '/edit') }}'>
						{{$stock_tag->tag_name}}
					</a>
			</td>
			<td>
					{{$stock_tag->tag_code}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_tags/delete/'. $stock_tag->tag_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stock_tags->appends(['search'=>$search])->render() }}
	@else
	{{ $stock_tags->render() }}
@endif
<br>
@if ($stock_tags->total()>0)
	{{ $stock_tags->total() }} records found.
@else
	No record found.
@endif
@endsection
