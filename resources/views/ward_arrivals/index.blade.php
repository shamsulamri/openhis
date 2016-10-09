@extends('layouts.app')

@section('content')
<h1>Ward Arrival List</h1>
<br>
<form action='/ward_arrival/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/ward_arrivals/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($ward_arrivals->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>arrival_description</th>
    <th>arrival_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($ward_arrivals as $ward_arrival)
	<tr>
			<td>
					<a href='{{ URL::to('ward_arrivals/'. $ward_arrival->arrival_id . '/edit') }}'>
						{{$ward_arrival->arrival_description}}
					</a>
			</td>
			<td>
					{{$ward_arrival->arrival_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('ward_arrivals/delete/'. $ward_arrival->arrival_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $ward_arrivals->appends(['search'=>$search])->render() }}
	@else
	{{ $ward_arrivals->render() }}
@endif
<br>
@if ($ward_arrivals->total()>0)
	{{ $ward_arrivals->total() }} records found.
@else
	No record found.
@endif
@endsection
