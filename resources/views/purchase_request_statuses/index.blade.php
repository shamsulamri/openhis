@extends('layouts.app')

@section('content')
<h1>Purchase Request Status Index
<a href='/purchase_request_statuses/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/purchase_request_status/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($purchase_request_statuses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>status_name</th>
    <th>status_code</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_request_statuses as $purchase_request_status)
	<tr>
			<td>
					<a href='{{ URL::to('purchase_request_statuses/'. $purchase_request_status->status_code . '/edit') }}'>
						{{$purchase_request_status->status_name}}
					</a>
			</td>
			<td>
					{{$purchase_request_status->status_code}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_request_statuses/delete/'. $purchase_request_status->status_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchase_request_statuses->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_request_statuses->render() }}
@endif
<br>
@if ($purchase_request_statuses->total()>0)
	{{ $purchase_request_statuses->total() }} records found.
@else
	No record found.
@endif
@endsection
