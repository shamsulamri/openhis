@extends('layouts.app')

@section('content')
<h1>
Delete Bed Booking
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $bed_booking->patient_id }}
{{ Form::open(['url'=>'bed_bookings/'.$bed_booking->book_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/bed_bookings" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
