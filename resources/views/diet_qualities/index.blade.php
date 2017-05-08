@extends('layouts.app')

@section('content')
<h1>Diet Quality List
<a href='/diet_qualities/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_quality/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($diet_qualities->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Period</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_qualities as $diet_quality)
	<tr>
			<td>
					<a href='{{ URL::to('diet_qualities/'. $diet_quality->qc_id . '/edit') }}'>
						{{ (DojoUtility::dateLongFormat($diet_quality->qc_date)) }}
					</a>
			</td>
			<td>
					{{$diet_quality->period_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_qualities/delete/'. $diet_quality->qc_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_qualities->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_qualities->render() }}
@endif
<br>
@if ($diet_qualities->total()>0)
	{{ $diet_qualities->total() }} records found.
@else
	No record found.
@endif
@endsection
