@extends('layouts.app')

@section('content')
<h1>Stock Input Line Index
<a href='/stock_input_lines/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/stock_input_line/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($stock_input_lines->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>line_id</th>
    <th>line_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($stock_input_lines as $stock_input_line)
	<tr>
			<td>
					<a href='{{ URL::to('stock_input_lines/'. $stock_input_line->line_id . '/edit') }}'>
						{{$stock_input_line->line_id}}
					</a>
			</td>
			<td>
					{{$stock_input_line->line_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_input_lines/delete/'. $stock_input_line->line_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $stock_input_lines->appends(['search'=>$search])->render() }}
	@else
	{{ $stock_input_lines->render() }}
@endif
<br>
@if ($stock_input_lines->total()>0)
	{{ $stock_input_lines->total() }} records found.
@else
	No record found.
@endif
@endsection
