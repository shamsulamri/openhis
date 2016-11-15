
@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@include('patients.id')
<h1>Dependant List / Manage</h1>
<div class="row">
	<div class="col-xs-6">
		<iframe name='frameIndex' id='frameIndex' width='99%' height='700px' src='/dependant/search?patient_id={{ $patient->patient_id }}'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameDetail' id='frameDetail' width='100%' height='700px' src='/dependants?patient_id={{ $patient->patient_id }}'><iframe>
	</div>
</div>
@endsection
