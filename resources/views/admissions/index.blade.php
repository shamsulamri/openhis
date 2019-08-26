@extends('layouts.app')

@section('content')
<h1>Admission List</h1>
@can('module-consultation')
<form action='/admission/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Ward</div></label>
						<div class='col-sm-9'>
							{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<div class='col-sm-12'>
									<button class="btn btn-primary" type="submit" value="Submit">Refresh</button>
						</div>
					</div>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
@endcan
@can('module-ward')
<div class="row">
	<div class="col-md-2">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Admission</strong></h5>	
				<h4><strong>{{ $wardHelper->totalAdmission() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Discharge</strong></h5>	
				<h4><strong>{{ $wardHelper->wardDischarge() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Available</strong></h5>	
				<h4><strong>{{ $wardHelper->bedAvailable() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Disabled Bed</strong></h5>	
				<h4><strong>{{ $bedHelper->bedDisabled($ward_code) }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Total Bed</strong></h5>	
				<h4><strong>{{ $wardHelper->totalBed() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Bed Occupancy</strong></h5>	
				<h4><strong>{{ $bedHelper->bedOccupancyRate($ward->department->department_code, DojoUtility::thisYear(), DojoUtility::thisMonth()) }}%</strong></h4>	
			</div>
		</div>
	</div>
</div>
@endcan
@can('module-patient')
<form action='/admission/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<div class='col-sm-12'>
							<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Ward</div></label>
						<div class='col-sm-9'>
							{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Type</div></label>
						<div class='col-sm-9'>
							{{ Form::select('admission_code', $admission_type, $admission_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
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
    <th>Panel</th>
    <th>Consultant</th>
	@cannot('module-ward')
    <th>Ward</th>
	@endcannot
	<!--
    <th>Room</th>
	-->
    <th>Bed</th>
	@can('module-ward')
	@if ($ward->ward_code != 'mortuary')
    <th>Diet</th>
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
					@can('module-ward')
					<a href='/admissions/{{ $admission->admission_id }}'>
					@else
					<a href='/admissions/{{ $admission->admission_id }}/edit'>
					@endcan
					{{ strtoupper($admission->patient_name) }}
					</a>
					<br>
					<small>{{ DojoUtility::formatMRN($admission->patient_mrn) }}
					<br>
					</small>
					@if ($bedHelper->reservationAvailable($admission))
							<div class='label label-primary'>
							Reserved Bed Availble
							</div>
					@endif

			<td>
					@if ($admission->encounter->sponsor)
						{{ $admission->encounter->sponsor->sponsor_name }}
					@endif
			</td>
			</td>
			<td>
					{{$admission->name}}
			</td>
			@cannot('module-ward')
			<td>
					{{$admission->ward_name}}
			</td>
			@endcannot
			<!--
			<td>
					@if ($admission->room_name) 
						{{$admission->room_name}} 
					@endif
			</td>
			-->
			<td>
				@can('module-ward')
					@if ($ward->ward_code != $admission->ward_code) 
						{{$admission->anchor_bed}} 
						<br>
						<span class='label label-success'>
						In {{ $admission->ward_name }}
						</span>
					@else
							
						{{$admission->bed_name}} 
					@endif
				@else
						{{$admission->bed_name}} 
				@endcan
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

			<td widht='10'>
			<div class='pull-right'>
			@can('module-consultation')
				@can('discharge_patient')
			<a class='btn btn-primary' title='Start consultation' href='{{ URL::to('admission/consultation/'.$admission->admission_id) }}'>
				<i class="fa fa-stethoscope"></i>
			</a>
				@endcan
			@endcan
			@can('system-administrator')
					<a class='btn btn-danger' href='{{ URL::to('admissions/delete/'. $admission->admission_id) }}'>Delete</a>
			@endcan
			@can('module-discharge')
							<a class='btn btn-primary ' href='{{ URL::to('deposits/index/'. $admission->patient_id) }}'>Deposit</a>
							<a class='btn btn-primary ' href='{{ URL::to('bill_items/'. $admission->encounter_id) }}'>Bill</a>
					<!--
					<a class='btn btn-primary' title='Start consultation' href='{{ URL::to('admission/consultation/'.$admission->admission_id) }}'>
						<i class="fa fa-stethoscope"></i>
					</a>
					-->
			@endcan
			</div>
			</td>
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
