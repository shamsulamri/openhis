@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Delete Booking
</h1>
<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
Booked on {{ date('d F Y', strtotime($bed_booking->created_at)) }}
{{ Form::open(['url'=>'bed_bookings/'.$bed_booking->book_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bed_bookings" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
