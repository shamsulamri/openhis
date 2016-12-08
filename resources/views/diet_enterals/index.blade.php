@extends('layouts.app')

@section('content')
<h1>Diet Enteral List
<a href='/diet_enterals/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_enteral/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
