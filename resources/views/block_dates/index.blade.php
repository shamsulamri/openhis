@extends('layouts.app')

@section('content')
<h1>Block Date List
<a href='/block_dates/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/block_date/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($block_dates->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>Date</th> 
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
			<td>
					{{ (DojoUtility::dateLongFormat($block_date->block_date)) }}
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
