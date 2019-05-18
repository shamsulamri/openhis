@extends('layouts.app')

@section('content')
<h1>
Delete Reservation
</h1>
<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
Booked on {{ (DojoUtility::dateLongFormat($bed_booking->created_at)) }}
{{ Form::open(['url'=>'bed_bookings/'.$bed_booking->book_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bed_bookings" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
