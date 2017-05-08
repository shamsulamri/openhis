@extends('layouts.app')

@section('content')
<h1>Diet Wastage List
<a href='/diet_wastages/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/diet_wastage/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($diet_wastages->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Ward</th> 
    <th>Period</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_wastages as $diet_wastage)
	<tr>
			<td>
					<a href='{{ URL::to('diet_wastages/'. $diet_wastage->waste_id . '/edit') }}'>
						{{ (DojoUtility::dateLongFormat($diet_wastage->waste_date)) }}
					</a>
			</td>
			<td>
					{{$diet_wastage->ward_name}}
			</td>
			<td>
					{{$diet_wastage->period_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_wastages/delete/'. $diet_wastage->waste_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_wastages->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_wastages->render() }}
@endif
<br>
@if ($diet_wastages->total()>0)
	{{ $diet_wastages->total() }} records found.
@else
	No record found.
@endif
@endsection
