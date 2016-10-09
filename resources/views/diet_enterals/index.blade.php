@extends('layouts.app')

@section('content')
<h1>Diet Enteral List</h1>
<br>
<form action='/diet_enteral/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/diet_enterals/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($diet_enterals->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_enterals as $diet_enteral)
	<tr>
			<td>
					<a href='{{ URL::to('diet_enterals/'. $diet_enteral->enteral_code . '/edit') }}'>
						{{$diet_enteral->enteral_name}}
					</a>
			</td>
			<td>
					{{$diet_enteral->enteral_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_enterals/delete/'. $diet_enteral->enteral_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_enterals->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_enterals->render() }}
@endif
<br>
@if ($diet_enterals->total()>0)
	{{ $diet_enterals->total() }} records found.
@else
	No record found.
@endif
@endsection
