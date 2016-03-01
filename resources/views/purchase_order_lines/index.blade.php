@extends('layouts.app')

@section('content')
<h1>Purchase Order Line Index</h1>
<br>
<form action='/purchase_order_line/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/purchase_order_lines/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($purchase_order_lines->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>purchase_id</th>
    <th>line_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_order_lines as $purchase_order_line)
	<tr>
			<td>
					<a href='{{ URL::to('purchase_order_lines/'. $purchase_order_line->line_id . '/edit') }}'>
						{{$purchase_order_line->purchase_id}}
					</a>
			</td>
			<td>
					{{$purchase_order_line->line_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_order_lines/delete/'. $purchase_order_line->line_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchase_order_lines->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_order_lines->render() }}
@endif
<br>
@if ($purchase_order_lines->total()>0)
	{{ $purchase_order_lines->total() }} records found.
@else
	No record found.
@endif
@endsection
