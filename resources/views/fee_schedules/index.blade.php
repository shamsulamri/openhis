@extends('layouts.app')

@section('content')
<h1>Fee Schedule Index
<a href='/fee_schedules/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/fee_schedule/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($fee_schedules->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>fee_code</th>
    <th>fee_code</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($fee_schedules as $fee_schedule)
	<tr>
			<td>
					<a href='{{ URL::to('fee_schedules/'. $fee_schedule->fee_code . '/edit') }}'>
						{{$fee_schedule->fee_code}}
					</a>
			</td>
			<td>
					{{$fee_schedule->fee_code}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('fee_schedules/delete/'. $fee_schedule->fee_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $fee_schedules->appends(['search'=>$search])->render() }}
	@else
	{{ $fee_schedules->render() }}
@endif
<br>
@if ($fee_schedules->total()>0)
	{{ $fee_schedules->total() }} records found.
@else
	No record found.
@endif
@endsection
