@extends('layouts.app')

@section('content')
<h1>Period List
<a href='/periods/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/period/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($periods->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($periods as $period)
	<tr>
			<td>
					<a href='{{ URL::to('periods/'. $period->period_code . '/edit') }}'>
						{{$period->period_name}}
					</a>
			</td>
			<td>
					{{$period->period_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('periods/delete/'. $period->period_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $periods->appends(['search'=>$search])->render() }}
	@else
	{{ $periods->render() }}
@endif
<br>
@if ($periods->total()>0)
	{{ $periods->total() }} records found.
@else
	No record found.
@endif
@endsection
