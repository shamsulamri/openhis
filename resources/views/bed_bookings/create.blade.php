@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
{{ $title }}
</h1>
<br>
@include('common.errors')
{{ Form::model($bed_booking, ['url'=>'bed_bookings', 'class'=>'form-horizontal']) }} 
    
	@include('bed_bookings.bed_booking')
{{ Form::close() }}

@endsection
