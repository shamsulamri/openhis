@extends('layouts.app')

@section('content')
<h1>Bed Request List</h1>
<br>
<form action='/bed_booking/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@include('common.notification')
@if ($bed_bookings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Patient</th>
	<th>Class</th>
	<th>Availability</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_bookings as $bed_booking)
	<?php
	$status="";
	if ($class_availability[$bed_booking->class_code]>0) {
		$status='success';
	}
	?>
	<tr class='{{ $status }}'>
			<td>
					{{ date('d F, H:i', strtotime($bed_booking->created_at)) }}
			</td>
			<td>
					<a href='{{ URL::to('bed_bookings/'. $bed_booking->book_id . '/edit') }}'>
						{{$bed_booking->patient_name}}
					</a>
			</td>
			<td>
					{{$bed_booking->class_name }}
			</td>
			<td>
					{{ $class_availability[$bed_booking->class_code] }}
					<?php
					$class_availability[$bed_booking->class_code] -=1;
					?>
			</td>
			<td align='right'>
					@if ($class_availability[$bed_booking->class_code]>0)
					<a class='btn btn-default btn-xs' href='{{ URL::to('admission_beds?flag=1&admission_id='.$bed_booking->admission_id.'&book_id='.$bed_booking->book_id) }}'>&nbsp; Move &nbsp;</a>
					@endif
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bed_bookings/delete/'. $bed_booking->book_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bed_bookings->appends(['search'=>$search])->render() }}
	@else
	{{ $bed_bookings->render() }}
@endif
<br>
@if ($bed_bookings->total()>0)
	{{ $bed_bookings->total() }} records found.
@else
	No record found.
@endif
@endsection
