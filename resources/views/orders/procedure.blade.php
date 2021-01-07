@extends('layouts.app')

@section('content')	
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@if (!empty($consultation))
@can('module-consultation')
		@include('consultations.panel')		
		<h1>Orders</h1>
@endcan
@endif

@include('orders.tab')

@if ($consultation->encounter->bill_close || $consultation->encounter->lock_orders)
		@include('orders.order_stop')
@else
		@include('consultation_procedures.procedure')
@endif
@endsection
