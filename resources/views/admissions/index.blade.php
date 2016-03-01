@extends('layouts.app')

@section('content')
<h1>Admission Index</h1>
<br>
<form action='/admission/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/admissions/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>bed_code</th>
    <th>admission_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($admissions as $admission)
	<tr>
			<td>
					<a href='{{ URL::to('admissions/'. $admission->admission_id . '/edit') }}'>
						{{$admission->bed_code}}
					</a>
			</td>
			<td>
					{{$admission->admission_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('admissions/delete/'. $admission->admission_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $admissions->appends(['search'=>$search])->render() }}
	@else
	{{ $admissions->render() }}
@endif
<br>
@if ($admissions->total()>0)
	{{ $admissions->total() }} records found.
@else
	No record found.
@endif
@endsection
