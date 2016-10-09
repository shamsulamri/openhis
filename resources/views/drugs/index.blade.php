@extends('layouts.app')

@section('content')
<h1>Drug List</h1>
<br>
<form action='/drug/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/drugs/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($drugs->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drugs as $drug)
	<tr>
			<td>
					<a href='{{ URL::to('drugs/'. $drug->drug_code . '/edit') }}'>
						{{$drug->drug_generic_name}}
					</a>
			</td>
			<td>
					{{$drug->drug_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drugs/delete/'. $drug->drug_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drugs->appends(['search'=>$search])->render() }}
	@else
	{{ $drugs->render() }}
@endif
<br>
@if ($drugs->total()>0)
	{{ $drugs->total() }} records found.
@else
	No record found.
@endif
@endsection
