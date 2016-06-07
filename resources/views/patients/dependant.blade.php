
@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #C0C0C0 solid; }
</style>
@include('patients.id')
<h1>Patient Dependants</h1>
<br>
<a class='btn btn-default' href='/patients/{{ $patient->patient_id }}'>Return<a/>
<br>
<br>
<div class="row">
	<div class="col-xs-6">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='800px' src='/dependant/search?patient_id={{ $patient->patient_id }}'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameDetail' id='frameDetail' width='100%' height='800px' src='/dependants?patient_id={{ $patient->patient_id }}'><iframe>
	</div>
</div>
@endsection
