@extends('layouts.app2')

@section('content')
<!--
<h1>Form Position List
<a href='/form_positions/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/form_position/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
-->

@if ($form_positions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>Index</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($form_positions as $form_position)
	<tr>
			<td>
					<a href='{{ URL::to('form_positions/'. $form_position->id).'/edit' }}'>
					{{$form_position->property_name}}
					</a>
			</td>
			<td>
					{{$form_position->property_code}}
			</td>
			<td>
					{{$form_position->property_position}}
			</td>

			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('form_positions/delete/'. $form_position->id) }}'><span class='glyphicon glyphicon-trash'></span></a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $form_positions->appends(['search'=>$search])->render() }}
	@else
	{{ $form_positions->render() }}
@endif
<br>
@if ($form_positions->total()>0)
	{{ $form_positions->total() }} records found.
@else
	No record found.
@endif
@endsection
