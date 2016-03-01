@extends('layouts.app')

@section('content')
<h1>Bed Booking Index</h1>
<br>
<form action='/bed_booking/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/bed_bookings/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($bed_bookings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>patient_id</th>
    <th>book_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_bookings as $bed_booking)
	<tr>
			<td>
					<a href='{{ URL::to('bed_bookings/'. $bed_booking->book_id . '/edit') }}'>
						{{$bed_booking->patient_id}}
					</a>
			</td>
			<td>
					{{$bed_booking->book_id}}
			</td>
			<td align='right'>
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
