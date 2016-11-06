@extends('layouts.app2')

@section('content')
<style>
.pagination {
    font-size: 60%;
}
</style>
<h3>{{ $set->set_name }}</h3>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($order_sets->total()>0)
<table class="table table-condensed">
 <thead>
	<tr> 
    <th>Product</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($order_sets as $order_set)
	<tr>
			<td>
					<a href='{{ URL::to('products/'. $order_set->product_code ) }}'>
						{{$order_set->product_name}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('order_sets/delete/'. $order_set->id) }}'>-</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_sets->appends(['search'=>$search])->render() }}
	@else
	{{ $order_sets->render() }}
@endif
<br>
@if ($order_sets->total()>0)
	{{ $order_sets->total() }} records found.
@else
	No record found.
@endif
@endsection
