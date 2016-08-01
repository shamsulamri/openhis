@extends('layouts.app')

@section('content')
<h1>Order Tasks</h1>
<!--
<form action='/order_queue/search' method='post'>
	{{ Form::select('locations', $locations, $location, ['class'=>'form-control input-lg','maxlength'=>'10']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<br>
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
</form>
-->
<h3>{{ $location->location_name }}</h3>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
@if ($order_queues->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>MRN</th>
    <th>Patient</th>
    <th>Source</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_queues as $order)

	<tr>
			<td>
					{{ $order->cancel_id }}
					{{ date('d F, H:i', strtotime($order->created_at)) }}
			</td>
			<td>
					{{ $order->patient_mrn }}
			</td>
			<td>
					{{$order->patient_name}}
			</td>
			<td>
					{{ $order->location_name }}{{ $order->bed_name }}
			</td>
			<td align='right'>
					<a href='{{ URL::to('order_tasks/task/'. $order->encounter_id) .'/'. $order->location_code }}' class='btn btn-primary btn-xs'>
						Open	
					</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_queues/delete/'. $order->order_id) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_queues->appends(['search'=>$search])->render() }}
	@else
	{{ $order_queues->render() }}
@endif
<br>
@if ($order_queues->total()>0)
	{{ $order_queues->total() }} records found.
@else
	No record found.
@endif
@endsection
