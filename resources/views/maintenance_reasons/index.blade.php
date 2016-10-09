@extends('layouts.app')

@section('content')
<h1>Maintenance Reason Index</h1>
<br>
<form action='/maintenance_reason/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/maintenance_reasons/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($maintenance_reasons->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Reason</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($maintenance_reasons as $maintenance_reason)
	<tr>
			<td>
					<a href='{{ URL::to('maintenance_reasons/'. $maintenance_reason->reason_code . '/edit') }}'>
						{{$maintenance_reason->reason_code}}
					</a>
			</td>
			<td>
					{{$maintenance_reason->reason_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('maintenance_reasons/delete/'. $maintenance_reason->reason_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $maintenance_reasons->appends(['search'=>$search])->render() }}
	@else
	{{ $maintenance_reasons->render() }}
@endif
<br>
@if ($maintenance_reasons->total()>0)
	{{ $maintenance_reasons->total() }} records found.
@else
	No record found.
@endif
@endsection
