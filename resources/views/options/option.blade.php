@extends('layouts.app')

@section('content')
<h1>Options</h1>
<h3>Patient Management</h3>
		<a href="{{ url('/patients') }}">Patients</a><br>
		<a href="{{ url('/encounters') }}">Encounters</a><br>
		<a href="{{ url('/queues') }}">Queues</a><br>
		<a href="{{ url('/queue_locations') }}">Queue Locations</a><br>
		<a href="{{ url('/admissions') }}">Admissions</a><br>
		<a href="{{ url('/admission_types') }}">Admission Types</a><br>
		<a href="{{ url('/referrals') }}">Referral Types</a><br>
		<a href="{{ url('/patient_types') }}">Patient Types</a><br>
		<a href="{{ url('/encounter_types') }}">Encounter Types</a><br>
<h3>Appointment & Scheduling</h3>
		<a href="{{ url('/appointments') }}">Appointments</a><br>
		<a href="{{ url('/appointment_services') }}">Appointment Services</a><br>
		<a href="{{ url('/block_dates') }}">Block Dates</a><br>
<h3>Electronic Medical Record</h3>
		<a href="{{ url('/consultations') }}">Consultation</a><br>
		<a href="{{ url('/consultation_diagnoses') }}">Consultation Diagnoses</a><br>
		<a href="{{ url('/consultation_procedures') }}">Consultation Procedures</a><br>
		<a href="{{ url('/consultation_orders') }}">Consultation Orders</a><br>
		<a href="{{ url('/diagnosis_types') }}">Diagnosis Types</a><br>
		<a href="{{ url('/newborns') }}">Newborns</a><br>
		<a href="{{ url('/birth_types') }}">Birth Types</a><br>
		<a href="{{ url('/birth_complications') }}">Birth Complications</a><br>
		<a href="{{ url('/delivery_modes') }}">Delivery Modes</a><br>
		<a href="{{ url('/medical_certificates') }}">Medical Certificates</a><br>
		<a href="{{ url('/medical_alerts') }}">Medical Alerts</a><br>
		<a href="{{ url('/triages') }}">Triages</a><br>
<h3>Ward Management</h3>
		<a href="{{ url('/departments') }}">Departments</a><br>
		<a href="{{ url('/wards') }}">Wards</a><br>
		<a href="{{ url('/ward_classes') }}">Ward Classes</a><br>
		<a href="{{ url('/rooms') }}">Rooms</a><br>
		<a href="{{ url('/beds') }}">Beds</a><br>
		<a href="{{ url('/bed_movements') }}">Bed Movements</a><br>
		<a href="{{ url('/bed_bookings') }}">Bed Bookings</a><br>
		<a href="{{ url('/bed_statuses') }}">Bed Statuses</a><br>
<h3>Inventory Management</h3>
		<a href="{{ url('/products') }}">Products</a><br>
		<a href="{{ url('/product_categories') }}">Product Categories</a><br>
		<a href="{{ url('/suppliers') }}">Suppliers</a><br>
		<a href="{{ url('/stores') }}">Stores</a><br>
		<a href="{{ url('/purchase_orders') }}">Purchase Orders</a><br>
		<a href="{{ url('/purchase_order_lines') }}">Purchase Order Lines</a><br>
		<a href="{{ url('/stocks') }}">Stocks</a><br>
		<a href="{{ url('/stock_movements') }}">Stock Movements</a><br>
		<a href="{{ url('/bill_materials') }}">Bill of Materials</a><br>
<h3>Order Management</h3>
		<a href="{{ url('/orders') }}">Orders</a><br>
		<a href="{{ url('/order_products') }}">Order Products</a><br>
		<a href="{{ url('/order_drugs') }}">Order Drugs</a><br>
		<a href="{{ url('/order_investigations') }}">Order Investigations</a><br>
		<a href="{{ url('/order_cancellations') }}">Order Cancellations</a><br>
		<a href="{{ url('/order_forms') }}">Order Forms</a><br>
		<a href="{{ url('/drugs') }}">Drugs</a><br>
		<a href="{{ url('/drug_prescriptions') }}">Drug Prescriptions</a><br>
		<a href="{{ url('/drug_categories') }}">Drugs Categories</a><br>
		<a href="{{ url('/drug_systems') }}">Drugs Systems</a><br>
		<a href="{{ url('/drug_dosages') }}">Drugs Dosages</a><br>
		<a href="{{ url('/drug_frequencies') }}">Drugs Frequencies</a><br>
		<a href="{{ url('/drug_routes') }}">Drugs Routes</a><br>
		<a href="{{ url('/forms') }}">Forms</a><br>
		<a href="{{ url('/form_properties') }}">Form Properties</a><br>
		<a href="{{ url('/form_positions') }}">Form Positions</a><br>
<h3>Diet Management</h3>
		<a href="{{ url('/diets') }}">Diets</a><br>
		<a href="{{ url('/diet_complains') }}">Diet Complains</a><br>
		<a href="{{ url('/diet_classes') }}">Diet Classes</a><br>
		<a href="{{ url('/diet_contaminations') }}">Diet Contaminations</a><br>
		<a href="{{ url('/diet_enterals') }}">Diet Enterals</a><br>
		<a href="{{ url('/diet_meals') }}">Diet Meals</a><br>
		<a href="{{ url('/diet_ratings') }}">Diet Ratings</a><br>
		<a href="{{ url('/diet_periods') }}">Diet Periods</a><br>
		<a href="{{ url('/diet_textures') }}">Diet Textures</a><br>
		<a href="{{ url('/diet_wastages') }}">Diet Wastages</a><br>
		<a href="{{ url('/diet_qualities') }}">Diet Qualities</a><br>
<h3>Maintenance</h3>
		<a href="{{ url('/care_levels') }}">Care Levels</a><br>
		<a href="{{ url('/cities') }}">Cities</a><br>
		<a href="{{ url('/genders') }}">Genders</a><br>					
		<a href="{{ url('/races') }}">Races</a><br>
		<a href="{{ url('/religions') }}">Religions</a><br>
		<a href="{{ url('/states') }}">States</a><br>
		<a href="{{ url('/nations') }}">Nations</a><br>
		<a href="{{ url('/occupations') }}">Occupations</a><br>
		<a href="{{ url('/tourists') }}">Tourist</a><br>
		<a href="{{ url('/marital_statuses') }}">Marital Statuses</a><br>
		<a href="{{ url('/titles') }}">Titles</a><br>
		<a href="{{ url('/registrations') }}">Registrations</a><br>
		<a href="{{ url('/employers') }}">Employers</a><br>
		<a href="{{ url('/care_organisations') }}">Care Organisations</a><br>
		<a href="{{ url('/unit_measures') }}">Unit Measures</a><br>
		<a href="{{ url('/periods') }}">Periods</a><br>
		<a href="{{ url('/frequencies') }}">Frequencies</a><br>
		<a href="{{ url('/urgencies') }}">Urgencies</a><br>
		<a href="{{ url('/relationships') }}">Relationships</a><br>
@endsection
