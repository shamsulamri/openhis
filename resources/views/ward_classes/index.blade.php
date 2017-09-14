@extends('layouts.app')

@section('content')
<h1>Class List
<a href='/ward_classes/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/ward_class/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($ward_classes->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
    <th>Price</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($ward_classes as $ward_class)
	<tr>
			<td>
					<a href='{{ URL::to('ward_classes/'. $ward_class->class_code . '/edit') }}'>
						{{$ward_class->class_name}}
					</a>
			</td>
			<td>
					{{$ward_class->class_code}}
			</td>
			<td>
					{{number_format($ward_class->class_price,2)}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('ward_classes/delete/'. $ward_class->class_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $ward_classes->appends(['search'=>$search])->render() }}
	@else
	{{ $ward_classes->render() }}
@endif
<br>
@if ($ward_classes->total()>0)
	{{ $ward_classes->total() }} records found.
@else
	No record found.
@endif
@endsection
