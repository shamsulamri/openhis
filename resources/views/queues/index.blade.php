@extends('layouts.app')

@section('content')
<h1>Queue Index</h1>
<br>
<form action='/queue/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/queues/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($queues->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Patient</th>
    <th>Location</th>
    <th>Time</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($queues as $queue)
	<tr>
			<td>

					<a href='{{ URL::to('queues/'. $queue->queue_id . '/edit') }}'>
						{{$queue->patient_name}}
					</a>
			</td>
			<td>
					{{$queue->location_name}}
			</td>
			<td>
					{{ date('d F Y, H:i', strtotime($queue->created_at)) }}
			</td>
			<td align='right'>
					@if ($queue->consultation_id>0)
						<a class='btn btn-primary btn-xs' href='{{ URL::to('consultations/'. $queue->consultation_id .'/edit') }}'>Consult</a>
					@else
						<a class='btn btn-primary btn-xs' href='{{ URL::to('consultations/create?encounter_id='. $queue->encounter_id) }}'>Consult</a>
					@endif
					<a class='btn btn-danger btn-xs' href='{{ URL::to('queues/delete/'. $queue->queue_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $queues->appends(['search'=>$search])->render() }}
	@else
	{{ $queues->render() }}
@endif
<br>
@if ($queues->total()>0)
	{{ $queues->total() }} records found.
@else
	No record found.
@endif
@endsection
