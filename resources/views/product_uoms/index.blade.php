@extends('layouts.app')

@section('content')
<h1>Product Uom Index
<a href='/product_uoms/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/product_uom/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($product_uoms->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>product_code</th>
    <th>id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_uoms as $product_uom)
	<tr>
			<td>
					<a href='{{ URL::to('product_uoms/'. $product_uom->id . '/edit') }}'>
						{{$product_uom->product_code}}
					</a>
			</td>
			<td>
					{{$product_uom->id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_uoms/delete/'. $product_uom->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_uoms->appends(['search'=>$search])->render() }}
	@else
	{{ $product_uoms->render() }}
@endif
<br>
@if ($product_uoms->total()>0)
	{{ $product_uoms->total() }} records found.
@else
	No record found.
@endif
@endsection
