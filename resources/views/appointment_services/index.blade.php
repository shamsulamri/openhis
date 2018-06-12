@extends('layouts.app')

@section('content')
<h1>Appointment Service List
<div class='pull-right'>
<a href='/appointment_services/forget' class='btn btn-primary'><span class='fa fa-stop-circle'></span></a>
<a href='/appointment_services/create' class='btn btn-primary'><span class='glyphicon glyphicon-plus'></span></a>
</div>
</h1>
<form action='/appointment_service/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($appointment_services->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Service</th>
    <th>Department</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($appointment_services as $appointment_service)
	<tr>
			<td>
					<a href='{{ URL::to('appointment_services/'. $appointment_service->service_id . '/edit') }}'>
						{{$appointment_service->service_name}}
					</a>
			</td>
			<td>
					{{$appointment_service->department_name}}
			</td>
			<td align='right'>
					<a class='btn btn-warning btn-xs' href='{{ URL::to('appointment_services/set/'. $appointment_service->service_id) }}'>Set Book</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('appointment_services/delete/'. $appointment_service->service_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $appointment_services->appends(['search'=>$search])->render() }}
	@else
	{{ $appointment_services->render() }}
@endif
<br>
@if ($appointment_services->total()>0)
	{{ $appointment_services->total() }} records found.
@else
	No record found.
@endif
@endsection
