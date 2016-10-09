@extends('layouts.app')

@section('content')
<h1>Patient Type List</h1>
<br>
<form action='/patient_type/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/patient_types/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($patient_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($patient_types as $patient_type)
	<tr>
			<td>
					<a href='{{ URL::to('patient_types/'. $patient_type->type_code . '/edit') }}'>
						{{$patient_type->type_name}}
					</a>
			</td>
			<td>
					{{$patient_type->type_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('patient_types/delete/'. $patient_type->type_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patient_types->appends(['search'=>$search])->render() }}
	@else
	{{ $patient_types->render() }}
@endif
<br>
@if ($patient_types->total()>0)
	{{ $patient_types->total() }} records found.
@else
	No record found.
@endif
@endsection
