@extends('layouts.app')

@section('content')
<h1>Bill Discount Index
<a href='/bill_discounts/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/bill_discount/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($bill_discounts->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>encounter_id</th>
    <th>discount_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bill_discounts as $bill_discount)
	<tr>
			<td>
					<a href='{{ URL::to('bill_discounts/'. $bill_discount->discount_id . '/edit') }}'>
						{{$bill_discount->encounter_id}}
					</a>
			</td>
			<td>
					{{$bill_discount->discount_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bill_discounts/delete/'. $bill_discount->discount_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bill_discounts->appends(['search'=>$search])->render() }}
	@else
	{{ $bill_discounts->render() }}
@endif
<br>
@if ($bill_discounts->total()>0)
	{{ $bill_discounts->total() }} records found.
@else
	No record found.
@endif
@endsection
