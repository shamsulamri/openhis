@extends('layouts.app')

@section('content')
<h1>Diet List
<a href='/diets/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
