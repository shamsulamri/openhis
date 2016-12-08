@extends('layouts.app')

@section('content')
<h1>Discharge Type List
<a href='/discharge_types/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/discharge_type/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($discharge_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Discharge Type</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($discharge_types as $discharge_type)
	<tr>
			<td>
					<a href='{{ URL::to('discharge_types/'. $discharge_type->type_code . '/edit') }}'>
						{{$discharge_type->type_name}}
					</a>
			</td>
			<td>
					{{$discharge_type->type_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('discharge_types/delete/'. $discharge_type->type_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $discharge_types->appends(['search'=>$search])->render() }}
	@else
	{{ $discharge_types->render() }}
@endif
<br>
@if ($discharge_types->total()>0)
	{{ $discharge_types->total() }} records found.
@else
	No record found.
@endif
@endsection
