@extends('layouts.app')

@section('content')
<h1>Discharge Index</h1>
<br>
<form action='/discharge/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/discharges/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($discharges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>type_code</th>
    <th>discharge_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($discharges as $discharge)
	<tr>
			<td>
					<a href='{{ URL::to('discharges/'. $discharge->discharge_id . '/edit') }}'>
						{{$discharge->type_code}}
					</a>
			</td>
			<td>
					{{$discharge->discharge_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('discharges/delete/'. $discharge->discharge_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $discharges->appends(['search'=>$search])->render() }}
	@else
	{{ $discharges->render() }}
@endif
<br>
@if ($discharges->total()>0)
	{{ $discharges->total() }} records found.
@else
	No record found.
@endif
@endsection
