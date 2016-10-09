@extends('layouts.app')

@section('content')
<h1>Block Date List</h1>
<br>
<form action='/block_date/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/block_dates/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($block_dates->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($block_dates as $block_date)
	<tr>
			<td>
					<a href='{{ URL::to('block_dates/'. $block_date->block_code . '/edit') }}'>
						{{$block_date->block_name}}
					</a>
			</td>
			<td>
					{{$block_date->block_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('block_dates/delete/'. $block_date->block_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $block_dates->appends(['search'=>$search])->render() }}
	@else
	{{ $block_dates->render() }}
@endif
<br>
@if ($block_dates->total()>0)
	{{ $block_dates->total() }} records found.
@else
	No record found.
@endif
@endsection
