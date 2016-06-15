@extends('layouts.app')

@section('content')	
<style>
iframe { border: 1px #C0C0C0 solid; }
</style>
@can('module-consultation')
		@include('consultations.panel')		
		<h1>Orders</h1>
		<br>
@endcan
@cannot('module-consultation')
		@include('patients.id')
		<h1>Edit Orders</h1>
		<br>
        <a class="btn btn-default" href="/order_tasks/task/{{ Session::get('encounter_id') }}/{{Cookie::get('queue_location')}}" role="button">Back to Task</a>
		<br>
		<br>
@endcannot
<div class="row">
	<div class="col-xs-6">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='750px' src='/order_products'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameDetail' id='frameDetail' width='100%' height='750px' src='/orders'><iframe>
	</div>
</div>


@endsection
