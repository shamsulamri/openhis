@extends('layouts.app')

@section('content')
<h1>Diet Wastage List</h1>
<br>
<form action='/diet_wastage/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/diet_wastages/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($diet_wastages->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>waste_date</th>
    <th>waste_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_wastages as $diet_wastage)
	<tr>
			<td>
					<a href='{{ URL::to('diet_wastages/'. $diet_wastage->waste_id . '/edit') }}'>
						{{$diet_wastage->waste_date}}
					</a>
			</td>
			<td>
					{{$diet_wastage->waste_id}}
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
