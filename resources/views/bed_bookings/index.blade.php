@extends('layouts.app')

@section('content')
@can('module-ward')
<h1>Bed Reservations</h1>
<h3>{{ $ward->ward_name }}</h3>
@endcan
@can('module-patient')
<h1>Preadmissions</h1>
@endcan
<br>
<form action='/bed_booking/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::hidden('is_preadmission', $is_preadmission) }}
</form>
<br>
@include('common.notification')
@if ($bed_bookings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Patient</th>
    <th>Reservation</th>
	@can('module-ward')
	<th><div align='right'>Vacant</div></th>
	@endcan
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
						{{strtoupper($bed_booking->patient_name)}}
					</a>
					<br>
					<small>
					{{$bed_booking->patient_mrn }}
					</small>
			</td>
			<td>
					{{$bed_booking->ward_name }}
					<br>
					<small>
					{{$bed_booking->class_name }} 
					@if ($bed_booking->bed_name)
						({{$bed_booking->bed_name }})
					@endif
					</small>
			</td>
			@can('module-ward')
			<td align='right'>
					{{ $bedVacant }}
			</td>
			@endcan
			<td align='right'>
					
					@can('module-ward')
					@if ($bedVacant>0 && !empty($bed_booking->admission_id))
					<a class='btn btn-default btn-xs' href='{{ URL::to('admission_beds?move=1&flag=1&admission_id='.$bed_booking->admission_id.'&book_id='.$bed_booking->book_id) }}'>&nbsp; Move &nbsp;</a>
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
