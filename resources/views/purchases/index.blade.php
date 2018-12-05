@extends('layouts.app')

@section('content')
<h1>Purchase Index
<a href='/purchases/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/purchase/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($purchases->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Document Number</th>
    <th>Type</th> 
    <th>Supplier</th> 
    <th>Description</th> 
    <th>Reference</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchases as $purchase)
	<tr>
			<td>
					<a href='{{ URL::to('purchases/'. $purchase->purchase_id.'/edit') }}'>
						{{$purchase->purchase_number}}
					</a>
			</td>
			<td>
					{{$purchase->document->document_name }}
			</td>
			<td>
					{{$purchase->supplier->supplier_name }}
			</td>
			<td>
					{{$purchase->purchase_description }}
			</td>
			<td>
					{{$purchase->purchase_reference }}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('purchase_lines/show/'. $purchase->purchase_id) }}'>Line Item</a>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchases/delete/'. $purchase->purchase_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchases->appends(['search'=>$search])->render() }}
	@else
	{{ $purchases->render() }}
@endif
<br>
@if ($purchases->total()>0)
	{{ $purchases->total() }} records found.
@else
	No record found.
@endif
@endsection
