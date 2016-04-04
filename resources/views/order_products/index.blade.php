@extends('layouts.app')

@section('content')
@include('orders.panel')
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<form action='/order_product/search' method='post'>
	<div class="row">
			<div class="col-xs-12">
				<input type='text' class='form-control' placeholder="Enter product name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
			</div>
	</div>
<br>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name='set_value' value="{{ $set_value }}">
</form>
@foreach ($sets as $set)
	@if ($set->set_code == $set_value)
			<a class='btn btn-warning btn-xs' href='/order_product/search?set_code={{ $set->set_code }}'>{{ $set->set_name }}</a>
	@else
			<a class='btn btn-default btn-xs' href='/order_product/search?set_code={{ $set->set_code }}'>{{ $set->set_name }}</a>
	@endif
@endforeach
<br>
<br>

@if (!is_null($order_products))
		@if ($order_products->total()>0)
		<form action='/orders/multiple' method='post'>
				<table class="table table-hover">
					<tbody>
						@foreach ($order_products as $order_product)
							<tr>
									<td width='10'>
										{{ Form::checkbox($order_product->product_code, 1, null) }}
									</td>
									<td>
										<a href='{{ URL::to('orders/create/'. $order_product->product_code) }}'>
										{{ ucfirst(strtoupper($order_product->product_name)) }}
										</a>
									</td>
							</tr>
						@endforeach
				@endif
				</tbody>
				</table>
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			<input type='hidden' name='_set_value' value="{{ $set_value }}">
			<input type='hidden' name='_page' value="{{ $page }}">
			<input type='hidden' name='_search' value="{{ $search }}">
			{{ Form::submit('Add Selection', ['class'=>'btn btn-primary']) }}
		</form>
		<br>
		{{ $order_products->appends(['search'=>$search, 'set_code'=>$set_value])->render() }}
		<br>
		@if ($order_products->total()>0)
			{{ $order_products->total() }} records found.
		@else
			No record found.
		@endif
@endif
@endsection
