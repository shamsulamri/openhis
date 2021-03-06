@extends('layouts.app')

@section('content')
<h1>Bed Enquiry</h1>
<br>
<div class="row">
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Total Bed</strong></h5>	
				<h4><strong>{{ $bedHelper->totalBed() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Available</strong></h5>	
				<h4><strong>{{ $bedHelper->bedAvailable() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Clinical Discharge</strong></h5>	
				<h4><strong>{{ $bedHelper->dischargeClinical() }}</strong></h4>	
			</div>
		</div>
	</div>
</div>

<form id='form' action='/bed/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Bed code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Ward</label>
						<div class='col-sm-9'>
							{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Class</label>
						<div class='col-sm-9'>
							{{ Form::select('class_code', $class, $class_code, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Status</div></label>
						<div class='col-sm-9'>
							{{ Form::select('status_code', $status, $status_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<div class='col-sm-12'>
						</div>
					</div>
			</div>
	</div>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Bed</th>
    <th>Room</th> 
    <th>Class</th> 
    <th>Ward</th> 
    <th>Status</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($beds as $bed)
	<tr>
			<td>
					{{$bed->bed_code}}
			</td>
			<td>
					{{$bed->bed_name}}
			</td>
			<td>
					{{$bed->room_name }}
			</td>
			<td>
					{{$bed->class_name}}
			</td>
			<td>
					{{$bed->ward_name}}
			</td>
			<td>
					@if ($bed->status_name == 'Occupied')
					{{ $bed->patient_name }} ({{ $bed->patient_mrn }})
					@else
					{{$bed->status_name}}
					@endif
			</td>
	</tr>
@endforeach
</tbody>
</table>
{{ $beds->appends(['search'=>$search, 'ward_code'=>$ward_code, 'class_code'=>$class_code, 'status_code'=>$status_code])->render() }}
<br>
@if ($beds->total()>0)
	{{ $beds->total() }} records found.
@else
	No record found.
@endif
<script>
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
