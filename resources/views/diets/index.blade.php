@extends('layouts.app')

@section('content')
<h1>Diet List</h1>
<br>
<form action='/diet/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/diets/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($diets->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diets as $diet)
	<tr>
			<td>
					<a href='{{ URL::to('diets/'. $diet->diet_code . '/edit') }}'>
						{{$diet->diet_name}}
					</a>
			</td>
			<td>
					{{$diet->diet_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diets/delete/'. $diet->diet_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diets->appends(['search'=>$search])->render() }}
	@else
	{{ $diets->render() }}
@endif
<br>
@if ($diets->total()>0)
	{{ $diets->total() }} records found.
@else
	No record found.
@endif
@endsection
