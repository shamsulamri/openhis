@extends('layouts.app')

@section('content')
<h1>Document Status Index</h1>
<br>
<form action='/document_status/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/document_statuses/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($document_statuses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($document_statuses as $document_status)
	<tr>
			<td>
					<a href='{{ URL::to('document_statuses/'. $document_status->status_code . '/edit') }}'>
						{{$document_status->status_name}}
					</a>
			</td>
			<td>
					{{$document_status->status_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('document_statuses/delete/'. $document_status->status_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $document_statuses->appends(['search'=>$search])->render() }}
	@else
	{{ $document_statuses->render() }}
@endif
<br>
@if ($document_statuses->total()>0)
	{{ $document_statuses->total() }} records found.
@else
	No record found.
@endif
@endsection
