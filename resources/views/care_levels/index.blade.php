@extends('layouts.app')

@section('content')
<h1>Care Level List</h1>
<br>
<form action='/care_level/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/care_levels/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($care_levels->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>care_name</th>
    <th>care_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($care_levels as $care_level)
	<tr>
			<td>
					<a href='{{ URL::to('care_levels/'. $care_level->care_code . '/edit') }}'>
						{{$care_level->care_name}}
					</a>
			</td>
			<td>
					{{$care_level->care_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('care_levels/delete/'. $care_level->care_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $care_levels->appends(['search'=>$search])->render() }}
	@else
	{{ $care_levels->render() }}
@endif
<br>
@if ($care_levels->total()>0)
	{{ $care_levels->total() }} records found.
@else
	No record found.
@endif
@endsection
