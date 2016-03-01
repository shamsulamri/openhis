@extends('layouts.app')

@section('content')
<h1>Appointment Index</h1>
<br>
<form action='/appointment/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/appointments/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($appointments->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>patient_id</th>
    <th>appointment_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($appointments as $appointment)
	<tr>
			<td>
					<a href='{{ URL::to('appointments/'. $appointment->appointment_id . '/edit') }}'>
						{{$appointment->patient_id}}
					</a>
			</td>
			<td>
					{{$appointment->appointment_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('appointments/delete/'. $appointment->appointment_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $appointments->appends(['search'=>$search])->render() }}
	@else
	{{ $appointments->render() }}
@endif
<br>
@if ($appointments->total()>0)
	{{ $appointments->total() }} records found.
@else
	No record found.
@endif
@endsection
