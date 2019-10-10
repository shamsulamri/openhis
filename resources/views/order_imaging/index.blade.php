@extends('layouts.app')

@section('content')
<h1>Order Imaging Index
<a href='/order_imaging/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/order_imaging/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($order_imaging->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>side</th>
    <th>product_code</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($order_imaging as $order_imaging)
	<tr>
			<td>
					<a href='{{ URL::to('order_imaging/'. $order_imaging->product_code . '/edit') }}'>
						{{$order_imaging->side}}
					</a>
			</td>
			<td>
					{{$order_imaging->product_code}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_imaging/delete/'. $order_imaging->product_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_imaging->appends(['search'=>$search])->render() }}
	@else
	{{ $order_imaging->render() }}
@endif
<br>
@if ($order_imaging->total()>0)
	{{ $order_imaging->total() }} records found.
@else
	No record found.
@endif
@endsection
