@extends('layouts.app')

@section('content')	
@include('patients.label')
@include('consultations.panel')
@if (Session::has('message'))
	<br>
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@else 
	<br>
@endif
<a href='/order_products/{{ $consultation->consultation_id }}' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($orders->total()>0)
<table class="table table-hover">
	<tbody>
@foreach ($orders as $order)
	<tr>
			<td>
					<a href='{{ URL::to('orders/'. $order->order_id . '/edit') }}'>
						{{ ucfirst(strtoupper($order->product_name)) }}
					</a>
			</td>
			<td>
					{{$order->product_code}}
			</td>
			<td align='right'>
					@if ($consultation->consultation_status=='1')
					<a class='btn btn-warning btn-xs' href='{{ URL::to('/order_cancellations/create/'. $order->order_id) }}'>Cancel</a>
					@else
					<a class='btn btn-danger btn-xs' href='{{ URL::to('orders/delete/'. $order->order_id) }}'>Delete</a>
					@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $orders->appends(['search'=>$search])->render() }}
	@else
	{{ $orders->render() }}
@endif
<br>
@if ($orders->total()>0)
	{{ $orders->total() }} records found.
@else
	No record found.
@endif
@endsection
