@extends('layouts.app')

@section('content')
<h1>Queries & Reports</h1>
<br>

<h3>Clinical Order</h3>
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
		<a href="{{ url('/admission/diet_enquiry') }}">Diet Enquiry</a><br>
		<a href="{{ url('/diet_census/enquiry') }}">Diet Census Enquiry</a><br>
		<a href="{{ url('/bed/enquiry') }}">Bed Enquiry</a><br>
		<a href="{{ url('/bed_movement/enquiry') }}">Bed Movement History</a><br>
		<a href="{{ url('/preadmission/enquiry') }}">Preadmission Enquiry</a><br>
	</div>
	<div class="col-xs-6">
		<a href="{{ url('/discharge/discharge_count') }}">Discharge Count Report</a><br>
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

<h3>Billing</h3>
<h3>
<div class="row">
	<div class="col-xs-6">
		<a href="{{ url('/bill/enquiry') }}">Bill Enquiry</a><br>
		<a href="{{ url('/bill_item/enquiry') }}">Charge Enquiry</a><br>
		<a href="{{ url('/payment/enquiry') }}">Payment Enquiry</a><br>
		<a href="{{ url('/deposit/enquiry') }}">Deposit Enquiry</a><br>
		<a href="{{ url('/refund/enquiry') }}">Refund Enquiry</a><br>
		<a href="{{ url('/bill_aging/enquiry') }}">Aging Report</a><br>
	</div>
	<div class="col-xs-6">
		<a href="{{ url('/bill/sponsor_outstanding') }}">Sponsor Outstanding Bill Report</a><br>
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
		<a href="{{ url('/inventory/enquiry') }}">Stock Movement Enquiry</a><br>
		<a href="{{ url('/purchase_line/enquiry') }}">Purchase Enquiry</a><br>
		<a href="{{ url('/products/reorder') }}">Product Reorder Enquiry</a><br>
		<a href="{{ url('/loan/enquiry') }}">Loan - Request Enquiry</a><br>
		<a href="{{ url('/loan/workload') }}">Loan - Workload Enquiry</a><br>
	</div>
</div>
</h3>
@endsection
