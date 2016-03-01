@extends('layouts.app')

@section('content')
<h1>
Edit Bed Booking
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_booking, ['route'=>['bed_bookings.update',$bed_booking->book_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bed_bookings.bed_booking')
{{ Form::close() }}

@endsection
