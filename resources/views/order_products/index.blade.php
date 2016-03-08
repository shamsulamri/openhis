@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')

{{ Form::label('Product Search', 'Product Search',['class'=>'control-label']) }}
<form action='/order_product/search' method='post'>
	<input type='text' class='form-control' placeholder="Enter product name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name='consultation_id' value="{{ $consultation->consultation_id }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($order_products->total()>0)
<table class="table table-hover">
	<tbody>
@foreach ($order_products as $order_product)
	<tr>
			<td>
				{{ ucfirst(strtoupper($order_product->product_name)) }}
			</td>
			<td>
					{{$order_product->product_code}}
			</td>
			<td align='right'>
					<a class='btn btn-primary btn-xs' href='{{ URL::to('orders/create/'.$consultation->consultation_id.'/'. $order_product->product_code) }}'>Select</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $order_products->appends(['search'=>$search, 'consultation_id'=>$consultation->consultation_id])->render() }}
	@else
	{{ $order_products->appends(['consultation_id'=>$consultation->consultation_id])->render() }}
@endif
<br>
@if ($order_products->total()>0)
	{{ $order_products->total() }} records found.
@else
	No record found.
@endif
@endsection
