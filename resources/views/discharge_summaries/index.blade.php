@extends('layouts.app')

@section('content')
<h1>Discharge Summary Index
<a href='/discharge_summaries/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/discharge_summary/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($discharge_summaries->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>summary_treatment</th>
    <th>encounter_id</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($discharge_summaries as $discharge_summary)
	<tr>
			<td>
					<a href='{{ URL::to('discharge_summaries/'. $discharge_summary->encounter_id . '/edit') }}'>
						{{$discharge_summary->summary_treatment}}
					</a>
			</td>
			<td>
					{{$discharge_summary->encounter_id}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('discharge_summaries/delete/'. $discharge_summary->encounter_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $discharge_summaries->appends(['search'=>$search])->render() }}
	@else
	{{ $discharge_summaries->render() }}
@endif
<br>
@if ($discharge_summaries->total()>0)
	{{ $discharge_summaries->total() }} records found.
@else
	No record found.
@endif
@endsection
