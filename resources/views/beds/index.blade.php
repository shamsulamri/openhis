@extends('layouts.app')

@section('content')
<h1>Bed List</h1>
<br>
<form action='/bed/search' method='post'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<br>
	{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
@can('system-administrator')
<a href='/beds/create' class='btn btn-primary'>Create</a>
<br>
<br>
@endcan
@if ($beds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Ward</th> 
    <th>Patient</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($beds as $bed)
	<tr>
			<td>
					<a href='{{ URL::to('beds/'. $bed->bed_code . '/edit') }}'>
						{{$bed->bed_name}}
					</a>
			</td>
			<td>
					{{$bed->ward_name}}
			</td>
			<td>
					{{ $bedHelper->occupiedBy($bed->bed_code, $bed->ward_code) }}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('beds/delete/'. $bed->bed_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $beds->appends(['search'=>$search])->render() }}
	@else
	{{ $beds->render() }}
@endif
<br>
@if ($beds->total()>0)
	{{ $beds->total() }} records found.
@else
	No record found.
@endif
@endsection
