@extends('layouts.app')

@section('content')
<h1>Bed Bookings </h1>
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
    <th>Ward</th>
	<th>Class</th>
	<th>Bed</th>
	<th><div align='right'>Vacant</div></th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_bookings as $bed_booking)
	<?php
	$status="";
	$bedVacant = $bedController->bedVacant($bed_booking->ward_code, $bed_booking->class_code);
	if ($bedVacant>0) {
		$status='success';
	}
	?>
	<tr class='{{ $status }}'>
			<td>
				@if ($bed_booking->admission_id==0)
					{{ date('d F Y', strtotime($bed_booking->book_date)) }}
				@else
					{{ date('d F, H:i', strtotime($bed_booking->book_date)) }}
				@endif
			</td>
			<td>
					<a href='{{ URL::to('bed_bookings/'. $bed_booking->book_id . '/edit') }}'>
						{{$bed_booking->patient_name}}
					</a>
			</td>
			<td>
					{{$bed_booking->ward_name }}
			</td>
			<td>
					{{$bed_booking->class_name }}
			</td>
			<td>
					{{$bed_booking->bed_name }}
			</td>
			<td align='right'>
					{{ $bedVacant }}
			</td>
			<td align='right'>
					
					@can('module-ward')
					@if ($bedVacant>0)
					<a class='btn btn-default btn-xs' href='{{ URL::to('admission_beds?flag=1&admission_id='.$bed_booking->admission_id.'&book_id='.$bed_booking->book_id) }}'>&nbsp; Move &nbsp;</a>
					@endif
					@endcan
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
