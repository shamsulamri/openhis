@extends('layouts.app')

@section('content')
<h1>Purchase Line Index
<a href='/purchase_lines/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/purchase_line/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($purchase_lines->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>product_code</th>
    <th>line_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_lines as $purchase_line)
	<tr>
			<td>
					<a href='{{ URL::to('purchase_lines/'. $purchase_line->line_id . '/edit') }}'>
						{{$purchase_line->product_code}}
					</a>
			</td>
			<td>
					{{$purchase_line->line_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_lines/delete/'. $purchase_line->line_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchase_lines->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_lines->render() }}
@endif
<br>
@if ($purchase_lines->total()>0)
	{{ $purchase_lines->total() }} records found.
@else
	No record found.
@endif
@endsection
