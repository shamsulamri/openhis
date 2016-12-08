@extends('layouts.app')

@section('content')
<h1>Unit Measure List
<a href='/unit_measures/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/unit_measure/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($unit_measures->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($unit_measures as $unit_measure)
	<tr>
			<td>
					<a href='{{ URL::to('unit_measures/'. $unit_measure->unit_code . '/edit') }}'>
						{{$unit_measure->unit_name}}
					</a>
			</td>
			<td>
					{{$unit_measure->unit_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('unit_measures/delete/'. $unit_measure->unit_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $unit_measures->appends(['search'=>$search])->render() }}
	@else
	{{ $unit_measures->render() }}
@endif
<br>
@if ($unit_measures->total()>0)
	{{ $unit_measures->total() }} records found.
@else
	No record found.
@endif
@endsection
