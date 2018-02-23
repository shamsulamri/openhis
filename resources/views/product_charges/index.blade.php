@extends('layouts.app')

@section('content')
<h1>Tier List
<a href='/product_charges/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/product_charge/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($product_charges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>charge_name</th>
    <th>charge_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($product_charges as $product_charge)
	<tr>
			<td>
					<a href='{{ URL::to('product_charges/'. $product_charge->charge_code . '/edit') }}'>
						{{$product_charge->charge_name}}
					</a>
			</td>
			<td>
					{{$product_charge->charge_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('product_charges/delete/'. $product_charge->charge_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $product_charges->appends(['search'=>$search])->render() }}
	@else
	{{ $product_charges->render() }}
@endif
<br>
@if ($product_charges->total()>0)
	{{ $product_charges->total() }} records found.
@else
	No record found.
@endif
@endsection
