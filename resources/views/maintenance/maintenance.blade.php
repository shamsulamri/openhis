@extends('layouts.app')

@section('content')
<h1>Maintenance</h1>
<h3>Patient Management</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/patients') }}">Patients</a><br>
				<a href="{{ url('/encounters') }}">Encounters</a><br>
				<a href="{{ url('/queues') }}">Location Queues</a><br>
				<a href="{{ url('/order_queues') }}">Outpatient Tasks</a><br>
				<!--
				<a href="{{ url('/bills') }}">Bills</a><br>
				<a href="{{ url('/payments') }}">Payments</a><br>
				<a href="{{ url('/deposits') }}">Deposits</a><br>
				-->
				<a href="{{ url('/sponsors') }}">Sponsors</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/queue_locations') }}">Queue Locations</a><br>
				<a href="{{ url('/admission_types') }}">Admission Types</a><br>
				<a href="{{ url('/referrals') }}">Referral Types</a><br>
				<a href="{{ url('/patient_types') }}">Patient Types</a><br>
				<a href="{{ url('/encounter_types') }}">Encounter Types</a><br>
				<a href="{{ url('/patient_flags') }}">Patient Flags</a><br>
				<!--
				<a href="{{ url('/ward_discharges') }}">Ward Discharges</a><br>
				<a href="{{ url('/ward_arrivals') }}">Ward Arrivals</a><br>
				-->
			</small>
			</div>
	</div>
	</h4>
<h3>Appointment & Scheduling</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/appointments') }}">Appointments</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/appointment_services') }}">Appointment Services</a><br>
				<a href="{{ url('/block_dates') }}">Block Dates</a><br>
			</small>
			</div>
	</div>
	</h4>
<h3>Consultation</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/consultations') }}">Consultation</a><br>
				<a href="{{ url('/patient_lists') }}">Patient Lists</a><br>
				<a href="{{ url('/discharges') }}">Discharges</a><br>
				<a href="{{ url('/sets') }}">Order Sets</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/discharge_types') }}">Discharge Types</a><br>
				<a href="{{ url('/diagnosis_types') }}">Diagnosis Types</a><br>
				<a href="{{ url('/birth_types') }}">Birth Types</a><br>
				<a href="{{ url('/birth_complications') }}">Birth Complications</a><br>
				<a href="{{ url('/delivery_modes') }}">Delivery Modes</a><br>
				<a href="{{ url('/triages') }}">Triages</a><br>
				<a href="{{ url('/patient_classifications') }}">Patient Classifications</a><br>
			</small>
			</div>
	</div>
	</h4>
<h3>Inventory & Order Management</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/products') }}">Products</a><br>
				<a href="{{ url('/order_maintenances') }}">Orders</a><br>
				<a href="{{ url('/purchases') }}">Purchases</a><br>
				<a href="{{ url('/purchase_orders') }}">Purchase Orders</a><br>
				<a href="{{ url('/suppliers') }}">Suppliers</a><br>
				<a href="{{ url('/stores') }}">Stores</a><br>
				<a href="{{ url('/order_routes') }}">Order Routes</a><br>
				<a href="{{ url('/loans') }}">Loans</a><br>
				<a href="{{ url('/product_authorizations') }}">Product Authorizations</a><br>
				<a href="{{ url('/store_authorizations') }}">Store Authorizations</a><br>
				<a href="{{ url('/product_price_tiers') }}">Price Tiers</a><br>
				<a href="{{ url('/inventory_batches') }}">Stock Batches</a><br>
				<a href="{{ url('/order_imaging') }}">Order Imaging</a><br>
			</div>
			<div class="col-xs-3">
			<small>
				<a href="{{ url('/purchase_documents') }}">Purchase Documents</a><br>
				<a href="{{ url('/purchase_request_statuses') }}">Purchase Request Statuses</a><br>
				<a href="{{ url('/product_categories') }}">Product Categories</a><br>
				<a href="{{ url('/product_groups') }}">Product Groups</a><br>
				<a href="{{ url('/product_statuses') }}">Product Statuses</a><br>
				<a href="{{ url('/stock_movements') }}">Stock Movements</a><br>
				<a href="{{ url('/stock_tags') }}">Stock Tags</a><br>
				<a href="{{ url('/order_forms') }}">Order Forms</a><br>
				<a href="{{ url('/maintenance_reasons') }}">Maintenance Reasons</a><br>
				<a href="{{ url('/loan_statuses') }}">Loan Statuses</a><br>
				<a href="{{ url('/loan_types') }}">Loan Types</a><br>
				<a href="{{ url('/tax_codes') }}">Tax Codes</a><br>
				<a href="{{ url('/tax_types') }}">Tax Types</a><br>
				<a href="{{ url('/general_ledgers') }}">General Ledger Codes</a><br>
				<a href="{{ url('/product_charges') }}">Tiers</a><br>
			</small>
			</div>
	</div>
	</h4>
<h3>Pharmacy</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/drugs') }}">Drugs</a><br>
				<a href="{{ url('/drug_prescriptions') }}">Drug Prescriptions</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/drug_categories') }}">Drug Categories</a><br>
				<a href="{{ url('/drug_systems') }}">Drug Systems</a><br>
				<a href="{{ url('/drug_dosages') }}">Drug Dosages</a><br>
				<a href="{{ url('/drug_frequencies') }}">Drug Frequencies</a><br>
				<a href="{{ url('/drug_routes') }}">Drug Routes</a><br>
				<a href="{{ url('/drug_cautions') }}">Drug Cautions</a><br>
				<a href="{{ url('/drug_indications') }}">Drug Indications</a><br>
				<a href="{{ url('/drug_instructions') }}">Drug Instructions</a><br>
				<a href="{{ url('/drug_special_instructions') }}">Drug Special Instructions</a><br>
			</small>
			</div>
	</div>
	</h4>
<br>
<h3>Form</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/forms') }}">Forms</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/form_systems') }}">Form systems</a><br>
			</small>
			</div>
	</div>
	</h4>
<br>
<h3>Billing Management</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/bills') }}">Bills</a><br>
				<a href="{{ url('/payments') }}">Payments</a><br>
				<a href="{{ url('/refunds') }}">Refunds</a><br>
				<a href="{{ url('/payment_credits') }}">Credit Payments</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/payment_methods') }}">Payment Methods</a><br>
				<a href="{{ url('/credit_cards') }}">Credit Cards</a><br>
			</small>
			</div>
	</div>
	</h4>
<br>
<h3>Ward Management</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/admissions') }}">Admissions</a><br>
				<a href="{{ url('/bed_bookings') }}">Bed Bookings</a><br>
				<a href="{{ url('/admission_tasks') }}">Admission Tasks</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/wards') }}">Wards</a><br>
				<a href="{{ url('/ward_classes') }}">Classes</a><br>
				<a href="{{ url('/rooms') }}">Rooms</a><br>
				<a href="{{ url('/beds') }}">Beds</a><br>
				<a href="{{ url('/departments') }}">Departments</a><br>
				<a href="{{ url('/bed_statuses') }}">Bed Statuses</a><br>
				<a href="{{ url('/bed_transactions') }}">Bed Transactions</a><br>
			</div>
	</div>
	</h4>
<h3>Diet Management</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/diet_menus') }}">Diet Menu</a><br>
				<a href="{{ url('/diet_orders') }}">Diet Orders</a><br>
				<a href="{{ url('/diet_cooklist') }}">Diet Cooklist</a><br>
				<a href="{{ url('/diet_bom') }}">Diet Bill of Material</a><br>
				<a href="{{ url('/diet_workorder') }}">Diet Work Order</a><br>
				<a href="{{ url('/diet_distribution') }}">Diet Distribution</a><br>
				<a href="{{ url('/diet_complains') }}">Diet Complains</a><br>
				<a href="{{ url('/diet_wastages') }}">Diet Wastages</a><br>
				<a href="{{ url('/diet_qualities') }}">Diet Qualities</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/diets') }}">Diets</a><br>
				<a href="{{ url('/diet_classes') }}">Diet Classes</a><br>
				<a href="{{ url('/diet_contaminations') }}">Diet Contaminations</a><br>
				<a href="{{ url('/diet_enterals') }}">Diet Enterals</a><br>
				<a href="{{ url('/diet_meals') }}">Diet Meals</a><br>
				<a href="{{ url('/diet_ratings') }}">Diet Ratings</a><br>
				<a href="{{ url('/diet_periods') }}">Diet Periods</a><br>
				<a href="{{ url('/diet_textures') }}">Diet Textures</a><br>
				<a href="{{ url('/diet_therapeutics') }}">Therapeutic Diets</a><br>
			</small>
			</div>
	</div>
	</h4>
<h3>Medical Record</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/documents') }}">Documents</a><br>
			</div>
			<div class="col-xs-6">
			<small>
				<a href="{{ url('/document_types') }}">Document Types</a><br>
				<a href="{{ url('/document_statuses') }}">Document Statuses</a><br>
			</div>
	</div>
	</h4>
<h3>Maintenance</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/cities') }}">Cities</a><br>
				<a href="{{ url('/genders') }}">Genders</a><br>					
				<a href="{{ url('/races') }}">Races</a><br>
				<a href="{{ url('/religions') }}">Religions</a><br>
				<a href="{{ url('/states') }}">States</a><br>
				<a href="{{ url('/nations') }}">Nations</a><br>
				<a href="{{ url('/occupations') }}">Occupations</a><br>
				<a href="{{ url('/marital_statuses') }}">Marital Statuses</a><br>
				<a href="{{ url('/titles') }}">Titles</a><br>
				<a href="{{ url('/unit_measures') }}">Unit Measures</a><br>
				<a href="{{ url('/periods') }}">Periods</a><br>
				<a href="{{ url('/frequencies') }}">Frequencies</a><br>
				<a href="{{ url('/urgencies') }}">Urgencies</a><br>
				<a href="{{ url('/relationships') }}">Relationships</a><br>
				<a href="{{ url('/priorities') }}">Priorities</a><br>
				<a href="{{ url('/entitlements') }}">Entitlements</a><br>
				<a href="{{ url('/histories') }}">Histories</a><br>
			</div>
	</div>
	</h4>
<h3>User Management</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="{{ url('/users') }}">Users</a><br>
				<a href="{{ url('/user_authorizations') }}">Authorization Groups</a><br>
				<a href="{{ url('/employees') }}">Employees</a><br>
				<!--
				<a href="{{ url('/teams') }}">Teams</a><br>
				<a href="{{ url('/team_members') }}">Team Members</a><br>
				-->
			</div>
	</div>
	</h4>
<h3>Downloads</h3>
	<h4>
	<div class="row">
			<div class="col-xs-6">
				<a href="MyKadSetup.exe">MyKad Setup</a><br>
			</div>
	</div>
	</h4>
<br>
<br>

@endsection
