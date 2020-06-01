@extends('layouts.app')

@section('content')	
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@if (!empty($consultation))
@can('module-consultation')
		@include('consultations.panel')		
		<h1>Plan</h1>
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

<!--
<ul class="nav nav-tabs">
  <li @if ($plan=='laboratory') class="active" @endif><a href="plan?plan=laboratory">Laboratory</a></li>
  <li @if ($plan=='imaging') class="active" @endif><a href="/imaging">Imaging</a></li>
  <li><a href="procedure">Procedures</a></li>
  <li><a href="medication">Medications</a></li>
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="plan?plan=fee_consultant">Fees</a></li>
  <li><a href="discussion">Discussion</a></li>
  <li class="active"><a href="make">Orders</a></li>
</ul>
<br>
-->

@include('orders.tab')
@if ($consultation->encounter->bill)
		@include('orders.order_stop')
@else
<div class="row">
	<div class="col-xs-6">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='850px' src='/order_products'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameDetail' id='frameDetail' width='100%' height='850px' src='/orders'><iframe>
	</div>
</div>
@endif


@endsection
