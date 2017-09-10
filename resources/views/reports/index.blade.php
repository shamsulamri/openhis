@extends('layouts.app')

@section('content')
<h1>Reports</h1>
<br>

<h3>Order Management</h3>
<h3>
<div class="row">
	<div class="col-xs-6">
		<a href="{{ url('/order/enquiry') }}">Order Enquiry</a><br>
	</div>
</div>
<br>

<h3>Admission, Discharge & Transfers</h3>
<h3>
<div class="row">
	<div class="col-xs-6">
		<a href="{{ url('/admission/enquiry') }}">Admission Enquiry</a><br>
		<a href="{{ url('/discharge/enquiry') }}">Discharge Enquiry</a><br>
		<a href="{{ url('/bed/enquiry') }}">Bed Enquiry</a><br>
		<a href="{{ url('/bed_movement/enquiry') }}">Bed Movement History</a><br>
		<a href="{{ url('/preadmission/enquiry') }}">Preadmission Enquiry</a><br>
	</div>
</div>
<br>

<h3>Appointments & Scheduling</h3>
<h3>
<div class="row">
	<div class="col-xs-6">
		<a href="{{ url('/appointment/enquiry') }}">Appointment Enquiry</a><br>
		<a href="{{ url('/queue/enquiry') }}">Queue Enquiry</a><br>
	</div>
</div>
<br>

</h3>
<h3>Inventory & Order Management</h3>
<h3>
<div class="row">
	<div class="col-xs-6">
		<a href="{{ url('/products/enquiry') }}">Product Enquiry</a><br>
		<a href="{{ url('/products/on_hand') }}">Stock On Hand Enquiry</a><br>
		<a href="{{ url('/stocks') }}">Stock Movement Enquiry</a><br>
		<a href="{{ url('/stock_batches') }}">Batch Number Enquiry</a><br>
		<a href="{{ url('/purchase_order_lines/enquiry') }}">Purchase Enquiry</a><br>
		<a href="{{ url('/products/reorder') }}">Reorder Enquiry</a><br>
	</div>
</div>
</h3>
@endsection
