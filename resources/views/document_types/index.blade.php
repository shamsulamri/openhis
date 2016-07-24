@extends('layouts.app')

@section('content')
<h1>Document Type Index</h1>
<br>
<form action='/document_type/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/document_types/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($document_types->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th> 
    <th>Code</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($document_types as $document_type)
	<tr>
			<td>
					<a href='{{ URL::to('document_types/'. $document_type->type_code . '/edit') }}'>
						{{$document_type->type_name}}
					</a>
			</td>
			<td>
						{{$document_type->type_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('document_types/delete/'. $document_type->type_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $document_types->appends(['search'=>$search])->render() }}
	@else
	{{ $document_types->render() }}
@endif
<br>
@if ($document_types->total()>0)
	{{ $document_types->total() }} records found.
@else
	No record found.
@endif
@endsection
