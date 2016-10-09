@extends('layouts.app')

@section('content')
<h1>Diet Texture List</h1>
<br>
<form action='/diet_texture/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/diet_textures/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($diet_textures->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_textures as $diet_texture)
	<tr>
			<td>
					<a href='{{ URL::to('diet_textures/'. $diet_texture->texture_code . '/edit') }}'>
						{{$diet_texture->texture_name}}
					</a>
			</td>
			<td>
					{{$diet_texture->texture_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_textures/delete/'. $diet_texture->texture_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_textures->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_textures->render() }}
@endif
<br>
@if ($diet_textures->total()>0)
	{{ $diet_textures->total() }} records found.
@else
	No record found.
@endif
@endsection
