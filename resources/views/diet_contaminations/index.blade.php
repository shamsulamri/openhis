@extends('layouts.app')

@section('content')
<h1>Diet Contamination List</h1>
<br>
<form action='/diet_contamination/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/diet_contaminations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($diet_contaminations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_contaminations as $diet_contamination)
	<tr>
			<td>
					<a href='{{ URL::to('diet_contaminations/'. $diet_contamination->contamination_code . '/edit') }}'>
						{{$diet_contamination->contamination_name}}
					</a>
			</td>
			<td>
					{{$diet_contamination->contamination_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_contaminations/delete/'. $diet_contamination->contamination_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_contaminations->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_contaminations->render() }}
@endif
<br>
@if ($diet_contaminations->total()>0)
	{{ $diet_contaminations->total() }} records found.
@else
	No record found.
@endif
@endsection
