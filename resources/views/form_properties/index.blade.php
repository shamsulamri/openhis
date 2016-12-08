@extends('layouts.app')

@section('content')
<h1>Form Property List
<a href='/form_properties/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/form_property/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($form_properties->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($form_properties as $form_property)
	<tr>
			<td>
					<a href='{{ URL::to('form_properties/'. $form_property->property_code . '/edit') }}'>
						{{$form_property->property_name}}
					</a>
			</td>
			<td>
					{{$form_property->property_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('form_properties/delete/'. $form_property->property_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $form_properties->appends(['search'=>$search])->render() }}
	@else
	{{ $form_properties->render() }}
@endif
<br>
@if ($form_properties->total()>0)
	{{ $form_properties->total() }} records found.
@else
	No record found.
@endif
@endsection
