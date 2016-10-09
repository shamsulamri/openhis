@extends('layouts.app')

@section('content')
<h1>Ward Discharge List</h1>
<br>
<form action='/ward_discharge/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/ward_discharges/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($ward_discharges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>discharge_description</th>
    <th>discharge_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($ward_discharges as $ward_discharge)
	<tr>
			<td>
					<a href='{{ URL::to('ward_discharges/'. $ward_discharge->discharge_id . '/edit') }}'>
						{{$ward_discharge->discharge_description}}
					</a>
			</td>
			<td>
					{{$ward_discharge->discharge_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('ward_discharges/delete/'. $ward_discharge->discharge_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $ward_discharges->appends(['search'=>$search])->render() }}
	@else
	{{ $ward_discharges->render() }}
@endif
<br>
@if ($ward_discharges->total()>0)
	{{ $ward_discharges->total() }} records found.
@else
	No record found.
@endif
@endsection
