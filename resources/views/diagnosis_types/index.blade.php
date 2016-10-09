@extends('layouts.app')

@section('content')
<h1>Diagnosis Type List</h1>
<br>
<form action='/diagnosis_type/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/diagnosis_types/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($diagnosis_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diagnosis_types as $diagnosis_type)
	<tr>
			<td>
					<a href='{{ URL::to('diagnosis_types/'. $diagnosis_type->type_code . '/edit') }}'>
						{{$diagnosis_type->type_name}}
					</a>
			</td>
			<td>
					{{$diagnosis_type->type_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diagnosis_types/delete/'. $diagnosis_type->type_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diagnosis_types->appends(['search'=>$search])->render() }}
	@else
	{{ $diagnosis_types->render() }}
@endif
<br>
@if ($diagnosis_types->total()>0)
	{{ $diagnosis_types->total() }} records found.
@else
	No record found.
@endif
@endsection
