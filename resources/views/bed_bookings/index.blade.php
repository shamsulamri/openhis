@extends('layouts.app')

@section('content')
<h1>Bed Reservations</h1>
<form action='/bed_booking/search' method='post'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($bed_bookings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Patient</th>
    <th>Reservation</th>
    <th>Type</th>
    <th><div align='middle'>Vacant</div></th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_bookings as $bed_booking)
	<?php
	$status="";
	$bedAvailable = $bedHelper->bedAvailable($bed_booking->ward_code, $bed_booking->class_code);
	if ($bedAvailable>0) {
		$status='success';
	}
	?>
	<tr class='{{ $status }}'>
			<td>
				@if ($bed_booking->admission_id==0)
					{{ (DojoUtility::dateLongFormat($bed_booking->book_date)) }}
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
					</small>
			</td>
			<td>
				{{ $bed_booking->book_preadmission?'Preadmission':'-' }}
			</td>
				
			<td align='middle'>
					{{ $bedAvailable }}
			</td>
			<td align='right'>
					
					<!--
					<a class='btn btn-primary btn-sm pull-right' data-toggle="tooltip" data-placement="top" title="Start Encounter" href='{{ URL::to('encounters/create?patient_id='. $bed_booking->patient_id.'&book_id='.$bed_booking->book_id) }}'>
						<i class="fa fa-stethoscope"></i>
					</a>
					-->
					@can('module-ward')
					@if ($bedAvailable>0 && !empty($bed_booking->admission_id))
					<a class='btn btn-default btn-sm' href='{{ URL::to('admission_beds?move=1&flag=1&admission_id='.$bed_booking->admission_id.'&book_id='.$bed_booking->book_id) }}'>&nbsp; Move &nbsp;</a>
					@endif
					@endcan
					<a class='btn btn-danger btn-sm' href='{{ URL::to('bed_bookings/delete/'. $bed_booking->book_id) }}'>Delete</a>
					&nbsp;
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
