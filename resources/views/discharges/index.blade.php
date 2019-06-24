@extends('layouts.app')

@section('content')
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
    <th>Queue Number</th>
    <th>Name</th> 
    <th>Discharge</th> 
    <th>Physician</th> 
    <th>Bill</th> 
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
				?>
				<span class='label label-{{ $label }}'>
				{{ $discharge->encounter->encounterType->encounter_name }}
				<span>
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
					{{ $discharge->encounter_description }}
			</td>
			<td>
					{{ strtoupper($discharge->patient_name) }}
					<br>
					<small>{{$discharge->patient_mrn}}</small>
			</td>
			<td>
					{{$discharge->type_name}}

			</td>
			<td>
					{{$discharge->name}}
					<br>
					<small>{{$discharge->ward_name}}</small>
			</td>
			<td>
			<?php
				$bill_status = $bill_helper->billStatus($discharge->encounter_id);
			?>
{{ $bill_status }}
			@if ($bill_status==0)
				<span class='label label-warning'>Open</span>
			@else 
				<span class='label label-default'>Paid</span>
			@endif
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
			@if ($discharge->mc_id && $bill_status==0)
<a class="btn btn-default pull-left" href="{{ Config::get('host.report_server') }}/ReportServlet?report=medical_certificate&id={{ $discharge->encounter_id }}" role="button" target="_blank">Medical Certificate</a>
			@endif
			@can('module-consultation')
			@if ($bill_status==0)
			<a class='btn btn-primary' title='Start consultation' href='{{ URL::to('consultations/create?encounter_id='. $discharge->encounter_id) }}'>
				<i class="fa fa-stethoscope"></i>
			</a>
			@endif
			@endcan
			@cannot('system-administrator')
			@if ($discharge->encounter_code=='inpatient')
					@can('module-discharge')
					<a class='btn btn-{{ $button_type }}' href='{{ URL::to('bill_items/'. $discharge->encounter_id) }}'>{{ $bill_label }}</a>
					@endcan
			@else
					@if ($dischargeHelper->drugCompleted($discharge->encounter_id))
							@can('module-discharge')
							<a class='btn btn-{{ $button_type }}' href='{{ URL::to('bill_items/'. $discharge->encounter_id) }}'>{{ $bill_label }}</a>
							@endcan
					@else
							<br>
							<span class="label label-warning">
							Preparing drug...
							</span>
					@endif
			@endif
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
@if (isset($search)) 
	{{ $discharges->appends(['search'=>$search])->render() }}
	@else
	{{ $discharges->render() }}
@endif
<br>
@if ($discharges->total()>0)
	{{ $discharges->total() }} records found.
@else
	No record found.
@endif
@endsection
