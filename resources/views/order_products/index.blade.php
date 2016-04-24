@extends('layouts.app2')

@section('content')
<style>
.pagination {
    font-size: 60%;
}
</style>

@if (Session::has('message'))
	<br>
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@else
	<br>
@endif
<form action='/order_product/search' method='post'>
	<div class="row">
			<div class="col-xs-12">
				<input type='text' class='form-control' placeholder="Enter product name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
			</div>
	</div>
	<br>
	<div class="row">
			<div class="col-xs-12">
            {{ Form::select('set_code', $sets,$set_value, ['class'=>'form-control','maxlength'=>'10']) }}
			</div>
	</div>
<br>
	<button class="btn btn-default btn-xs" type="submit" value="Submit">Refresh</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name='set_value' value="{{ $set_value }}">
</form>
<br>
@if (!is_null($order_products))
		@if ($order_products->total()>0)
		<form action='/orders/multiple' method='post'>
				<table class="table table-condensed">
					<tbody>
						@foreach ($order_products as $order_product)
							<tr>
									<!--
									<td width='10'>
										{{ Form::checkbox($order_product->product_code, 1, null) }}
									</td>
									-->
									<td>
										{{ ucfirst(strtoupper($order_product->product_name)) }}
									</td>
									<td width='10'>
										<a href='/orders/single/{{ $order_product->product_code }}?_search={{ $search }}&_page={{ $page }}&_set_value={{ $set_value }}' class='btn btn-primary btn-xs'>+</a>
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
			<!--
			{{ Form::submit('Add Selection', ['class'=>'btn btn-primary']) }}
			-->
		</form>
		@if ($order_products->total()>10)
		<br>
		{{ $order_products->appends(['search'=>$search, 'set_code'=>$set_value])->render() }}
		@endif
		<br>
		@if ($order_products->total()>0)
			{{ $order_products->total() }} records found.
		@else
			No record found.
		@endif
@endif
<script>
	var frame = parent.document.getElementById('frameDetail');
	frame.contentWindow.location.reload();
</script>
@endsection
