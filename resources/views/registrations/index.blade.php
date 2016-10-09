@extends('layouts.app')

@section('content')
<h1>Registration List</h1>
<br>
<form action='/registration/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/registrations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($registrations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($registrations as $registration)
	<tr>
			<td>
					<a href='{{ URL::to('registrations/'. $registration->registration_code . '/edit') }}'>
						{{$registration->registration_name}}
					</a>
			</td>
			<td>
					{{$registration->registration_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('registrations/delete/'. $registration->registration_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $registrations->appends(['search'=>$search])->render() }}
	@else
	{{ $registrations->render() }}
@endif
<br>
@if ($registrations->total()>0)
	{{ $registrations->total() }} records found.
@else
	No record found.
@endif
@endsection
