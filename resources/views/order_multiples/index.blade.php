@extends('layouts.app')

@section('content')
<h1>Order Multiple Index
<a href='/order_multiples/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/order_multiple/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($order_multiples->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>order_id</th>
    <th>multiple_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_multiples as $order_multiple)
	<tr>
			<td>
					<a href='{{ URL::to('order_multiples/'. $order_multiple->multiple_id . '/edit') }}'>
						{{$order_multiple->order_id}}
					</a>
			</td>
			<td>
					{{$order_multiple->multiple_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_multiples/delete/'. $order_multiple->multiple_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_multiples->appends(['search'=>$search])->render() }}
	@else
	{{ $order_multiples->render() }}
@endif
<br>
@if ($order_multiples->total()>0)
	{{ $order_multiples->total() }} records found.
@else
	No record found.
@endif
@endsection
