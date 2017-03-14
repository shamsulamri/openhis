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
				<h5><strong>Total Bed</strong></h5>	
				<h4><strong>{{ $wardHelper->totalBed() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Bed Available</strong></h5>	
				<h4><strong>{{ $wardHelper->bedAvailable() }}</strong></h4>	
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
					{{ date('d F, H:i', strtotime($admission->created_at)) }}
					<br>
					<small>
					<?php $ago =$dojo->diffForHumans($admission->created_at); ?> 
					{{ $ago }}
					</small>	
			</td>
			<td>
					<a href='{{ URL::to('admissions/'. $admission->admission_id.'/edit' ) }}'>
					{{ strtoupper($admission->patient_name) }}
					</a>
					<br>
					<small>{{$admission->patient_mrn}}
					<br>
					{{$admission->name}}</small>
			</td>
			<td>
					{{$admission->bed_name}} 
					@if ($admission->room_name) 
						/ {{$admission->room_name}} 
					@endif
					@cannot('module-ward')
					<br>
					<small>{{$admission->ward_name}}<small>	
					@endcannot
			</td>
			@can('module-ward')
			@if ($ward->ward_code != 'mortuary')
			<td>
					{{$admission->diet_name}}
			</td>
			@endif
			@endcan
			@can('module-ward')
			@if ($setWard == $ward->ward_code)
			<td align='right'>
						@can('system-administrator')
							<a class='btn btn-danger ' href='{{ URL::to('admissions/delete/'. $admission->admission_id) }}'>Delete</a>
						@endcan
						@if (is_null($admission->arrival_id) && empty($admission->discharge_id))
							<a class='btn btn-default btn-lg' href='{{ URL::to('ward_arrivals/create/'. $admission->encounter_id) }}' title='Log arrival'><span class='fa fa-sign-in' aria-hidden='true'></span>
</a>
						@endif
						@if (!empty($admission->discharge_id))
							<a class='btn btn-default btn-lg' href='{{ URL::to('ward_discharges/create/'. $admission->admission_id) }}' title='Ward discharge'><span class='fa fa-sign-out' aria-hidden='true'></span></a>
						@else
								@if (!is_null($admission->arrival_id)) 
								<a class='btn btn-default btn-lg' href="{{ URL::to('loans/request/'. $admission->patient_mrn.'?type=folder') }}" title='Folder request'><span class='glyphicon glyphicon-folder-close' aria-hidden='true'></span>
		</a>
								<a class='btn btn-default btn-lg' href='{{ URL::to('admission_beds?flag=1&admission_id='. $admission->admission_id) }}' title='Bed movement'><span class='glyphicon glyphicon-resize-horizontal' aria-hidden='true'></span>
		</a>
								<a class='btn btn-default btn-lg' title='Forms' href='{{ URL::to('admissions/'. $admission->admission_id) }}'><span class='fa fa-table' aria-hidden='true'></span></a>
								@endif
						@endif
			</td>
			@endif
			@endcan
			@can('module-discharge')
					@if (!$admission->discharge_id)
					<td align='right'>
							<a class='btn btn-primary ' href='{{ URL::to('bill_items/'. $admission->encounter_id) }}'>Interim Bill</a>
					</td>
					@endif
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
