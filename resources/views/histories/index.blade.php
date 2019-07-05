@extends('layouts.app')

@section('content')
<h1>History Index
<a href='/histories/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/history/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($histories->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>history_code</th>
    <th>history_code</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($histories as $history)
	<tr>
			<td>
					<a href='{{ URL::to('histories/'. $history->history_code . '/edit') }}'>
						{{$history->history_code}}
					</a>
			</td>
			<td>
					{{$history->history_code}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('histories/delete/'. $history->history_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $histories->appends(['search'=>$search])->render() }}
	@else
	{{ $histories->render() }}
@endif
<br>
@if ($histories->total()>0)
	{{ $histories->total() }} records found.
@else
	No record found.
@endif
@endsection
