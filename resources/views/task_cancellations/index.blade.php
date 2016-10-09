@extends('layouts.app')

@section('content')
<h1>Task Cancellation List</h1>
<br>
<form action='/task_cancellation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/task_cancellations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($task_cancellations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>order_id</th>
    <th>cancel_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($task_cancellations as $task_cancellation)
	<tr>
			<td>
					<a href='{{ URL::to('task_cancellations/'. $task_cancellation->cancel_id . '/edit') }}'>
						{{$task_cancellation->order_id}}
					</a>
			</td>
			<td>
					{{$task_cancellation->cancel_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('task_cancellations/delete/'. $task_cancellation->cancel_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $task_cancellations->appends(['search'=>$search])->render() }}
	@else
	{{ $task_cancellations->render() }}
@endif
<br>
@if ($task_cancellations->total()>0)
	{{ $task_cancellations->total() }} records found.
@else
	No record found.
@endif
@endsection
