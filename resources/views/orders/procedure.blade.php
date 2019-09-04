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

<ul class="nav nav-tabs">
  <li @if ($plan=='laboratory') class="active" @endif><a href="plan?plan=laboratory">Laboratory</a></li>
  <li @if ($plan=='imaging') class="active" @endif><a href="plan?plan=imaging">Imaging</a></li>
  <li class="active"><a href="procedure">Procedures</a></li>
  <li><a href="medication">Medications</a></li>
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="plan?plan=fee_consultant">Fees</a></li>
  <li><a href="discussion">Discussion</a></li>
  <li><a href="make">Orders</a></li>
</ul>
<br>
@include('consultation_procedures.procedure')
@endsection
