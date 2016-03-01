@extends('layouts.app')

@section('content')
<h1>
New Bed Booking
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_booking, ['url'=>'bed_bookings', 'class'=>'form-horizontal']) }} 
    
	@include('bed_bookings.bed_booking')
{{ Form::close() }}

@endsection
