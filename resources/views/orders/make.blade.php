@extends('layouts.app')

@section('content')	
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@if (!empty($consultation))
@can('module-consultation')
		@include('consultations.panel')		
		<h1>Orders</h1>
		<br>
@endcan
@endif
@cannot('module-consultation')
		@include('patients.id')
		<h1>Edit Orders</h1>
		<br>
        <a class="btn btn-primary" href="/order_tasks/task/{{ Session::get('encounter_id') }}/{{Cookie::get('queue_location')}}" role="button">Back to Task</a>
		<br>
		<br>
@endcannot

<div class="row">
	<div class="col-xs-6">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='850px' src='/order_products'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameDetail' id='frameDetail' width='100%' height='850px' src='/orders'><iframe>
	</div>
</div>


@endsection
