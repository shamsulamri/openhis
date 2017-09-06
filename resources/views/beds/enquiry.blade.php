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
				<h5><strong>Awaiting Discharge</strong></h5>	
				<h4><strong>{{ $bedHelper->wardDischarge() }}</strong></h4>	
			</div>
		</div>
	</div>
</div>

<form action='/bed/enquiry' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>&nbsp;Ward</label>
	{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>&nbsp;Class</label>
	{{ Form::select('class_code', $class, $class_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>&nbsp;Status</label>
	{{ Form::select('status_code', $status, $status_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-primary" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($beds->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Bed</th>
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
					{{$bed->bed_name}}
			</td>
			<td>
					{{$bed->class_name}}
			</td>
			<td>
					{{$bed->ward_name}}
			</td>
			<td>
					@if ($bed->status_code == '03')
					{{ $bedHelper->occupiedBy($bed->bed_code, $bed->ward_code) }}
					@else
					{{$bed->status_name}}
					@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $beds->appends(['search'=>$search])->render() }}
	@else
	{{ $beds->render() }}
@endif
<br>
@if ($beds->total()>0)
	{{ $beds->total() }} records found.
@else
	No record found.
@endif
@endsection
