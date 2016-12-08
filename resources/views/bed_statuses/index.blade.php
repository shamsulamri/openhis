@extends('layouts.app')

@section('content')
<h1>Bed Status List
<a href='/bed_statuses/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/bed_status/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($bed_statuses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_statuses as $bed_status)
	<tr>
			<td>
					<a href='{{ URL::to('bed_statuses/'. $bed_status->status_code . '/edit') }}'>
						{{$bed_status->status_name}}
					</a>
			</td>
			<td>
					{{$bed_status->status_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bed_statuses/delete/'. $bed_status->status_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bed_statuses->appends(['search'=>$search])->render() }}
	@else
	{{ $bed_statuses->render() }}
@endif
<br>
@if ($bed_statuses->total()>0)
	{{ $bed_statuses->total() }} records found.
@else
	No record found.
@endif
@endsection
