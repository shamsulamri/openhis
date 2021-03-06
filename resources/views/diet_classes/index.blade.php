@extends('layouts.app')

@section('content')
<h1>Diet Class List
<a href='/diet_classes/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_class/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($diet_classes->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_classes as $diet_class)
	<tr>
			<td>
					<a href='{{ URL::to('diet_classes/'. $diet_class->class_code . '/edit') }}'>
						{{$diet_class->class_name}}
					</a>
			</td>
			<td>
					{{$diet_class->class_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_classes/delete/'. $diet_class->class_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_classes->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_classes->render() }}
@endif
<br>
@if ($diet_classes->total()>0)
	{{ $diet_classes->total() }} records found.
@else
	No record found.
@endif
@endsection
