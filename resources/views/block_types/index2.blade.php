@extends('layouts.app')

@section('content')
<h1>Block Type Index
<a href='/block_types/create' class='btn btn-primary float-right'><span class='fas fa-plus'></span></a>
</h1>
<form action='/block_type/search' method='post'>
	<div class='input-group mb-3'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-append'>
			<button type="submit" class="btn btn-outline-secondary"> <span class='fas fa-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($block_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>block_code</th> 
    <th>block_name</th>
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($block_types as $block_type)
	<tr>
			<td>
					<a href='{{ URL::to('block_types/'. $block_type->block_code . '/edit') }}'>
							{{$block_type->block_code}}
					</a>
			</td>
			<td>
					{{$block_type->block_name}}
			</td>
			<td align='right'>
			@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('block_type/delete/'. $block_type->block_code) }}'>Delete</a>
			@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $block_types->appends(['search'=>$search])->render() }}
	@else
	{{ $block_types->render() }}
@endif
<br>
@if ($block_types->total()>0)
	{{ $block_types->total() }} records found.
@else
	No record found.
@endif
@endsection
