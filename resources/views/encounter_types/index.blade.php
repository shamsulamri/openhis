@extends('layouts.app')

@section('content')
<h1>Encounter Type List
<a href='/encounter_types/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/encounter_type/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($encounter_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($encounter_types as $encounter_type)
	<tr>
			<td>
					<a href='{{ URL::to('encounter_types/'. $encounter_type->encounter_code . '/edit') }}'>
						{{$encounter_type->encounter_name}}
					</a>
			</td>
			<td>
					{{$encounter_type->encounter_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('encounter_types/delete/'. $encounter_type->encounter_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $encounter_types->appends(['search'=>$search])->render() }}
	@else
	{{ $encounter_types->render() }}
@endif
<br>
@if ($encounter_types->total()>0)
	{{ $encounter_types->total() }} records found.
@else
	No record found.
@endif
@endsection
