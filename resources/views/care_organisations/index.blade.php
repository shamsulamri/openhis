@extends('layouts.app')

@section('content')
<h1>Care Organisation List</h1>
<br>
<form action='/care_organisation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/care_organisations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($care_organisations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>organisation_name</th>
    <th>organisation_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($care_organisations as $care_organisation)
	<tr>
			<td>
					<a href='{{ URL::to('care_organisations/'. $care_organisation->organisation_code . '/edit') }}'>
						{{$care_organisation->organisation_name}}
					</a>
			</td>
			<td>
					{{$care_organisation->organisation_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('care_organisations/delete/'. $care_organisation->organisation_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $care_organisations->appends(['search'=>$search])->render() }}
	@else
	{{ $care_organisations->render() }}
@endif
<br>
@if ($care_organisations->total()>0)
	{{ $care_organisations->total() }} records found.
@else
	No record found.
@endif
@endsection
