@extends('layouts.app')

@section('content')
<h1>Form Position List</h1>
<br>
<form action='/form_position/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/form_positions/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($form_positions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Form Name</th>
    <th>Property Name</th> 
    <th>Property Index</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($form_positions as $form_position)
	<tr>
			<td>
					<a href='{{ URL::to('form_positions/'. $form_position->id . '/edit') }}'>
						{{$form_position->form_name}}
					</a>
			</td>
			<td>
					{{$form_position->property_name}}
			</td>
			<td>
					{{$form_position->property_position}}
			</td>

			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('form_positions/delete/'. $form_position->id) }}'>Delete</a>
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
