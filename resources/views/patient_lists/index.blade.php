@extends('layouts.app')

@section('content')
<h1>Patient List</h1>
<h4>{{ $location->location_name }}</h4>
@if ($hasOpenOrders->count()>0)
<div class="alert alert-danger" role="alert">You have unposted order in {{ $hasOpenOrders->count() }} consultation(s). Please close pending consultation to post the order.
	<br>
	<br>
		@foreach($hasOpenOrders as $openOrder)
			<?php
				$order = $orderHelper->getOrder($openOrder->order_id);
				$patient = $order->consultation->encounter->patient;
			?>	
			- <a href='{{ URL::to('consultations/'. $order->consultation_id . '/edit') }}'>
				<strong>
				{{ $patient->getMRN() }}
				</strong>  
				{{ $patient->patient_name }}
			</a>
			@if ($openOrder != $hasOpenOrders[$hasOpenOrders->count()-1])
					<br>
			@endif
		@endforeach
</div>
@else
<br>
@endif
<div class="tabs-container">
		<ul class="nav nav-tabs">
				<li class="active">
					<a data-toggle="tab" href="#tab-1">Outpatient
						@if (count($outpatients)>0)
							 <label class="label label-primary">{{ count($outpatients) }}</label>
						@endif
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#tab-2">Inpatient
						@if (count($inpatients)>0)
							 <label class="label label-primary">{{ count($inpatients) }}</label>
						@endif
					</a>
				</li>
				<li>
					<a data-toggle="tab" href="#tab-3">Daycare
						@if (count($daycare)>0)
							 <label class="label label-primary">{{ count($daycare) }}</label>
						@endif
					</a>
				</li>
				@if ($location->encounter_code =='emergency')
				<li>
					<a data-toggle="tab" href="#tab-4">Observations
						@if (count($observations)>0)
							 <label class="label label-primary">{{ count($observations) }}</label>
						@endif
					</a>
				</li>
				@endif
				@if ($location->encounter_code =='mortuary')
				<li>
					<a data-toggle="tab" href="#tab-5">Mortuary
						@if (count($mortuary)>0 && $location->encounter_code=='mortuary')
							 <label class="label label-primary">{{ count($mortuary) }}</label>
						@endif
					</a>
				</li>
				@endif
		</ul>
		<div class="tab-content">
			<div id="tab-1" class="tab-pane active">
				<div class="panel-body">
						@if ($location->encounter_code !='mortuary')
						@include('patient_lists.outpatient')
						@endif
				</div>
			</div>
			<div id="tab-2" class="tab-pane">
				<div class="panel-body">
						<?php 
						$patients = $inpatients;
						$title = "Inpatient";
						?>
						@include('patient_lists.patients')
				</div>
			</div>
			<div id="tab-3" class="tab-pane">
				<div class="panel-body">
						<?php 
						$patients = $daycare;
						$title = "Daycare";
						?>
						@include('patient_lists.patients')
				</div>
			</div>
			@if ($location->encounter_code =='emergency')
			<div id="tab-4" class="tab-pane">
				<div class="panel-body">
						<?php 
						$patients = $observations;
						$title = "Observation";
						?>
						@include('patient_lists.patients')
				</div>
			</div>
			@endif
			@if ($location->encounter_code =='mortuary')
			<div id="tab-5" class="tab-pane">
				<div class="panel-body">
						<?php 
						$patients = $mortuary;
						$title = "Mortuary";
						?>
						@include('patient_lists.patients')
				</div>
			</div>
			@endif
		</div>
</div>
@endsection
