@extends('layouts.app')

@section('content')
<h1>Purchase Document Index
<a href='/purchase_documents/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/purchase_document/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($purchase_documents->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Name</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_documents as $purchase_document)
	<tr>
			<td>
					<a href='{{ URL::to('purchase_documents/'. $purchase_document->document_code . '/edit') }}'>
						{{$purchase_document->document_code}}
					</a>
			</td>
			<td>
					{{$purchase_document->document_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_documents/delete/'. $purchase_document->document_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchase_documents->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_documents->render() }}
@endif
<br>
@if ($purchase_documents->total()>0)
	{{ $purchase_documents->total() }} records found.
@else
	No record found.
@endif
@endsection
