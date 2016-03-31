@extends('layouts.app')

@section('content')
@if (Auth::user()->authorization->author_consultation==1)
		@include('patients.label')
		@include('consultations.panel')		
@else
		@include('patients.id')
@endif
<h2>
Delete Order
</h2>
@include('common.errors')
<br>
<h4>
Are you sure you want to delete the selected record ?
{{ $order->product_code }}
{{ Form::open(['url'=>'orders/'.$order->order_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/orders" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
