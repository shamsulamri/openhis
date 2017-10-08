@extends('layouts.app')

@section('content')
<h1>Admission List</h1>
@can('module-ward')
		@if ($ward)
		<h3>{{ $ward->ward_name }}</h3>
		<br>
		@endif
@endcan
@can('module-ward')
<div class="row">
	<div class="col-md-3">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Total Admission</strong></h5>	
				<h4><strong>{{ $wardHelper->totalAdmission() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Available / Total</strong></h5>	
				<h4><strong>{{ $wardHelper->bedAvailable() }} / {{ $wardHelper->totalBed() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Awaiting Discharge</strong></h5>	
				<h4><strong>{{ $wardHelper->wardDischarge() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Bed Occupancy Rate</strong></h5>	
				<h4><strong>{{ number_format($bedHelper->bedOccupancyRate($ward->department->department_code, DojoUtility::thisYear(), DojoUtility::thisMonth()),2) }}%</strong></h4>	
			</div>
		</div>
	</div>
</div>
@endcan
@can('module-patient')
<form action='/admission/search' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	@can('module-patient')
	<label>Ward</label>
	{{ Form::select('ward', $wards, $ward, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Type</label>
	{{ Form::select('admission_code', $admission_type, $admission_code, ['class'=>'form-control','maxlength'=>'10']) }}
	@endcan
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@endcan

@if ($admissions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th>
    <th>Patient</th>
    <th>Consultant</th>
	@cannot('module-ward')
    <th>Ward</th>
	@endcannot
    <th>Room</th>
    <th>Bed</th>
	@can('module-ward')
	@if ($ward->ward_code != 'mortuary')
    <th>Diet</th>
	@endif
	@endcan
	@can('module-ward')
	@if ($setWard == $ward->ward_code)
	<th></th>
	@endif
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($admissions as $admission)
	<?php $status='' ?>
	@can('module-ward')
	@if (is_null($admission->arrival_id)) 
			<?php $status='warning' ?>
	@endif
	@if (!is_null($admission->discharge_id)) 
			<?php $status='success' ?>
	@endif
	@endcan
	<tr class='{{ $status }}'>
			<td>
					{{ DojoUtility::dateTimeReadFormat($admission->created_at) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($admission->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td>
					{{ strtoupper($admission->patient_name) }}
					<br>
					<small>{{$admission->patient_mrn}}
					<br>
					</small>
			</td>
			<td>
					{{$admission->name}}
			</td>
			@cannot('module-ward')
			<td>
					{{$admission->ward_name}}
			</td>
			@endcannot
			<td>
					@if ($admission->room_name) 
						{{$admission->room_name}} 
					@endif
			</td>
			<td>
					{{$admission->bed_name}} 
			</td>
			@can('module-ward')
			@if ($ward->ward_code != 'mortuary')
			<td>
					@if ($admission->nbm_status==1)
					<div class='label label-danger'>
					Nil by Mouth
					@else

					@php ($label = "default")
					@if ($admission->diet_code != 'normal') 
							@php ($label = "danger")
					@else 
					@endif
					<div class='label label-{{ $label }}'>
					{{$admission->diet_name}}
					@endif
					</div>
			</td>
			@endif
			@endcan
			@can('module-ward')
			@if ($setWard == $ward->ward_code)
			<td align='right'>
						@if (is_null($admission->arrival_id) && empty($admission->discharge_id))
							<a class='btn btn-default btn-lg' href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}' title='Log arrival'><span class='fa fa-sign-in' aria-hidden='true'></span>
</a>
						@endif
						@if (!empty($admission->discharge_id))
							<a class='btn btn-default btn-lg' href='{{ URL::to('ward_discharges/create/'. $admission->admission_id) }}' title='Ward discharge'><span class='fa fa-sign-out' aria-hidden='true'></span></a>
						@else
								@if (!is_null($admission->arrival_id)) 
								<!--
								<a class='btn btn-default btn-lg' href="{{ URL::to('loans/request/'. $admission->patient_mrn.'?type=folder') }}" title='Folder request'><span class='glyphicon glyphicon-folder-close' aria-hidden='true'></span>
		</a>
-->
								<a class='btn btn-default btn-lg' href='{{ URL::to('admission_beds?flag=1&admission_id='. $admission->admission_id) }}' title='Bed movement'><span class='glyphicon glyphicon-resize-horizontal' aria-hidden='true'></span>
		</a>
								<a class='btn btn-default btn-lg' title='Forms' href='{{ URL::to('form/results/'. $admission->encounter_id) }}'><span class='fa fa-table' aria-hidden='true'></span></a>
									<?php
										$consultation = $wardHelper->hasOpenConsultation($admission->patient_id, $admission->encounter_id, Auth::user()->id);
									?>
									@if (empty($consultation))
										<a class='btn btn-default btn-lg' title='Consultation' href='{{ URL::to('consultations/create?encounter_id='. $admission->encounter_id) }}'><span class='fa fa-stethoscope' aria-hidden='true'></span></a>
										@else
										<a class='btn btn-warning btn-lg' href='{{ URL::to('consultations/'. $wardHelper->openConsultationId. '/edit') }}'><span class='fa fa-stethoscope' aria-hidden='true'></span></a>

									@endif
<a class='btn btn-default btn-lg'  target="_blank" href='{{ Config::get('host.report_server') }}/ReportServlet?report=consent_form&id={{ $admission->patient_id }}'>
<span class='glyphicon glyphicon-print' aria-hidden='true'></span>
</a>
								@endif
						@endif
						@can('system-administrator')
							<a class='btn btn-danger btn-sm ' href='{{ URL::to('admissions/delete/'. $admission->admission_id) }}'>Delete</a>
						@endcan
			</td>
			@endif
			@endcan
			@can('module-discharge')
					@if (!$admission->discharge_id)
					<td align='right' width='20'>
							<a class='btn btn-primary ' href='{{ URL::to('bill_items/'. $admission->encounter_id) }}'>Interim Bill</a>
					</td>
					@else
					<td align='right'>
					</td>
					@endif
			@endcan

			@can('module-patient')
			<td>
			@if (empty($admission->arrival_id))
					<a class='btn btn-danger pull-right btn-sm' href='{{ URL::to('admissions/delete/'. $admission->admission_id) }}'>Delete</a>
			@endif
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $admissions->appends(['search'=>$search])->render() }}
	@else
	{{ $admissions->render() }}
@endif
<br>
@if ($admissions->total()>0)
	{{ $admissions->total() }} records found.
@else
	No record found.
@endif
@endsection
