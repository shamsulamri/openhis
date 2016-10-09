@extends('layouts.app')

@section('content')
<h1>Triage List</h1>
<br>
<form action='/triage/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/triages/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($triages->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($triages as $triage)
	<tr>
			<td>
					<a href='{{ URL::to('triages/'. $triage->triage_code . '/edit') }}'>
						{{$triage->triage_name}}
					</a>
			</td>
			<td>
					{{$triage->triage_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('triages/delete/'. $triage->triage_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $triages->appends(['search'=>$search])->render() }}
	@else
	{{ $triages->render() }}
@endif
<br>
@if ($triages->total()>0)
	{{ $triages->total() }} records found.
@else
	No record found.
@endif
@endsection
