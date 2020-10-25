@extends('layouts.app')

@section('content')
<style>
.dropdown-menu.pull-left {
    left:0;
  }
</style>
<h1>Discharge List</h1>
<br>
<form action='/discharge/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Patient name or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Encounter</label>
						<div class='col-sm-9'>
								{{ Form::select('encounter_code', $encounters, $encounter_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Outcome</label>
						<div class='col-sm-9'>
								{{ Form::select('type_code', $discharge_types, $type_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
	</div>
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($discharges->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter</th>
    <th>Discharge</th>
    <th>Outcome</th> 
    <th>Queue Number</th>
    <th>Name</th> 
    <th>Panel</th> 
    <th>Physician</th> 
    <th>Bill Status</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($discharges as $discharge)
	<tr>
			<td>
				<?php 
				$label = 'warning'; 
				switch ($discharge->encounter->encounter_code) {
						case "inpatient":
								$label = 'success';
								break;
						case "outpatient":
								$label = 'info';
								break;
						default:
								$label = 'default';
								break;
				}
				$bill_status = $bill_helper->billStatus($discharge->encounter_id);
				?>
				<span class='label label-{{ $label }}'>
				{{ $discharge->encounter->encounterType->encounter_name }}
				</span>
			</td>
			<td>
					{{ (DojoUtility::dateLongFormat($discharge->discharge_date)) }}
					<br>
					<small>
					<?php $ago =DojoUtility::diffForHumans($discharge->created_at); ?> 
					{{ $ago }}
					</small>	
					<!--
					<a href='{{ URL::to('discharges/'. $discharge->discharge_id . '/edit') }}'>
					-->
					<!--
					</a>
					-->
			</td>
			<td>
					{{$discharge->type_name}}

			</td>
			<td>
					{{ $discharge->encounter_description }}
			</td>
			<td>
					{{ strtoupper($discharge->patient_name) }}
					<br>
					<small>{{$discharge->patient_mrn}}</small>
			@if (!empty($discharge->newborn_id))
				<br>
				<span class='label label-danger'>Surat Akuan Bersalin</span>
			@endif
			@if (!empty($discharge->mc_id))
				<br>
					@if (!empty($discharge->mc_start) && empty($discharge->mc_time_start))
						<span class='label label-danger'>Medical Certificate</span>
					@endif
					@if (!empty($discharge->mc_time_start))
						<span class='label label-danger'>Time Slip</span>
					@endif
			@endif
			@if ($discharge->encounter_code!='inpatient')
					@if (!$dischargeHelper->drugCompleted($discharge->encounter_id))
						@if ($bill_status==0)
							<br>
							<span class="label label-danger">
							Preparing drug...
							</span>
						@endif
					@endif
			@endif
			</td>
			<td>
					@if ($discharge->encounter->sponsor)
						{{ $discharge->encounter->sponsor->sponsor_name }}
					@endif
			</td>
			<td>
					{{ strtoupper($discharge->name) }}
					<br>
					<small>{{$discharge->ward_name}}</small>
			</td>
			<td> 
			@if ($bill_status==0)
				<span class='label label-warning'>Open</span>
			@else 
				<span class='label label-default'>Paid</span>
			@endif
			</td>
			<td>
			@can('module-consultation')
				@if ($bill_status==0)
						@cannot('system-administrator')
						<a class='btn btn-primary' href='{{ URL::to('consultations/create?encounter_id='. $discharge->encounter_id) }}'>
								<i class="fa fa-stethoscope"></i>
						</a>
						@endcannot
				@endif
			@endcan
			</td>
			<td align='right'>
			<?php
			$bill_label="Interim Bill";
			$button_type="primary";
			if ($discharge->discharge_id>0) {
				$bill_label = "Final Bill";
				/**
				if ($discharge->id>0) {
						$bill_label="&nbsp;&nbsp; Posted &nbsp;&nbsp;";
						$button_type="default";
				}
				**/
			}
			?>

			<!--
			@can('module-ward')
					@if (!empty($discharge->newborn_id))
						<a class="btn btn-default btn-xs" href="{{ Config::get('host.report_server') }}?report=akuan_bersalin&id={{ $discharge->encounter_id }}" role="button" target="_blank">Akuan Bersalin</a>
					@endif
					@if (!empty($discharge->mc_id))
							@if (!empty($discharge->mc_start) && empty($discharge->mc_time_start))
				<a class="btn btn-default btn-xs" href="{{ Config::get('host.report_server') }}?report=medical_certificate&id={{ $discharge->encounter_id }}" role="button" target="_blank">Medical Certificate</a>
							@endif
							@if (!empty($discharge->mc_time_start))
				<a class="btn btn-default btn-xs" href="{{ Config::get('host.report_server') }}?report=time_slip&id={{ $discharge->encounter_id }}" role="button" target="_blank">Time Slip</a>
							@endif
					@endif
			@endcan
			-->


	@can('module-ward')
		<div class="dropdown">
		  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				Print
				<span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
			@if (!empty($discharge->newborn_id))
			<li><a href="{{ Config::get('host.report_server') }}?report=akuan_bersalin&id={{ $discharge->encounter_id }}" target="_blank">Surat Akuan Bersalin</a></li>
			@endif
			@if (!empty($discharge->mc_id))
					@if (!empty($discharge->mc_start) && empty($discharge->mc_time_start))
			<li><a href="{{ Config::get('host.report_server') }}?report=medical_certificate&id={{ $discharge->encounter_id }}" target="_blank">Medical Certificate</a></li>
					@endif
					@if (!empty($discharge->mc_time_start))
			<li><a href="{{ Config::get('host.report_server') }}?report=time_slip&id={{ $discharge->encounter_id }}" target="_blank">Time Slip</a></li>
					@endif
			@endif
			<li><a href="{{ Config::get('host.report_server') }}?report=referral_letter&id={{ $discharge->encounter_id }}" target="_blank">Referral Letter</a></li>
			<li><a href="{{ Config::get('host.report_server') }}?report=reply_letter&id={{ $discharge->encounter_id }}" target="_blank">Reply Letter</a></li>
			<li><a href="{{ Config::get('host.report_server') }}?report=discharge_summary2&id={{ $discharge->encounter_id }}" target="_blank">Discharge Summary</a></li>
		  </ul>
		</div>
	@endcan

	@cannot('system-administrator')
			@can('module-discharge')
				<a class='btn btn-{{ $button_type }}' href='{{ URL::to('bill_items/'. $discharge->encounter_id) }}'>{{ $bill_label }}</a>
			@endcan
	@endcannot
	@can('system-administrator')
	<a class='btn btn-danger btn-xs' href='{{ URL::to('discharges/delete/'. $discharge->discharge_id) }}'>Delete</a>
	@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
	{{ $discharges->appends(['search'=>$search, 'encounter_code'=>$encounter_code, 'type_code'=>$type_code])->render() }}
<br>
@if ($discharges->total()>0)
	{{ $discharges->total() }} records found.
@else
	No record found.
@endif
@endsection
