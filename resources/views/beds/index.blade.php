@extends('layouts.app')

@section('content')
<h1>Bed List
<a href='/beds/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/bed/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
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
			<div class="col-xs-3">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Class</label>
						<div class='col-sm-9'>
							{{ Form::select('class_code', $class, $class_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-1">
					<div class='form-group'>
						<div class='col-sm-12'>
	<button class="btn btn-primary pull-right" type="submit" value="Submit"><span class='glyphicon glyphicon-search'></span></button>
						</div>
					</div>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>

<div class="row">
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Total Bed</strong></h5>	
				<h4><strong>{{ $bedHelper->totalBed($ward_code, $class_code) }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Available</strong></h5>	
				<h4><strong>{{ $bedHelper->bedAvailable($ward_code, $class_code) }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Clinical Discharge</strong></h5>	
				<h4><strong>{{ $bedHelper->dischargeClinical($ward_code, $class_code) }}</strong></h4>	
			</div>
		</div>
	</div>
</div>

@can('system-administrator')
<br>
@endcan
@if ($beds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
	<!--
    <th>Code</th>
	-->
    <th>Bed</th>
    <th>Room</th>
    <th>Class</th> 
    <th>Ward</th> 
    <th>Level</th> 
    <th>Status</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($beds as $bed)
	<tr>
			<!--
			<td>
					{{$bed->bed_code}}
			</td>
			-->
			<td>
					<a href='{{ URL::to('beds/'. $bed->bed_code . '/edit') }}'>
						{{$bed->bed_name}}
					</a>
			</td>
			<td>
					@if ($bed->room)
					{{$bed->room->room_name}}
					@else
					-
					@endif
			</td>
			<td>
					{{$bed->class_name}}
			</td>
			<td>
					{{$bed->ward->ward_name}}
			</td>
			<td>
					{{$bed->ward->ward_level}}
			</td>
			<td>
<?php
	$label = 'default';
	if ($bed->status_code=='02') $label = 'danger';
	if ($bed->status_code=='03') $label = 'primary';
	if ($bed->status_code=='05') $label = 'warning';
?>
					<span class="label label-{{ $label }}">
					@if ($bed->status_code == '03')
					{{ $bedHelper->occupiedBy($bed->bed_code, $bed->ward_code) }}
					@else
					{{$bed->status_name}}
					@endif
					</span>
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('beds/delete/'. $bed->bed_code) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $beds->appends(['search'=>$search, 'ward_code'=>$ward_code, 'class_code'=>$class_code])->render() }}
<br>
@if ($beds->total()>0)
	{{ $beds->total() }} records found.
@else
	No record found.
@endif
@endsection
