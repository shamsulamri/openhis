@extends('layouts.app')

@section('content')
<h1>Admission Type List
<a href='/admission_types/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/admission_type/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($admission_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($admission_types as $admission_type)
	<tr>
			<td>
					<a href='{{ URL::to('admission_types/'. $admission_type->admission_code . '/edit') }}'>
						{{$admission_type->admission_name}}
					</a>
			</td>
			<td>
					{{$admission_type->admission_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('admission_types/delete/'. $admission_type->admission_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $admission_types->appends(['search'=>$search])->render() }}
	@else
	{{ $admission_types->render() }}
@endif
<br>
@if ($admission_types->total()>0)
	{{ $admission_types->total() }} records found.
@else
	No record found.
@endif
@endsection
