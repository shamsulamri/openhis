@extends('layouts.app')

@section('content')
<h1>Bed List</h1>
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
<form action='/bed/search' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<label>Ward</label>
	{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Class</label>
	{{ Form::select('class_code', $class, $class_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<button class="btn btn-default" type="submit" value="Submit">Search</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@can('system-administrator')
<a href='/beds/create' class='btn btn-primary'>Create</a>
<br>
<br>
@endcan
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
					<a href='{{ URL::to('beds/'. $bed->bed_code . '/edit') }}'>
						{{$bed->bed_name}}
					</a>
			</td>
			<td>
					{{$bed->class_name}}
			</td>
			<td>
					{{$bed->ward_name}}
			</td>
			<td>
					@if ($bed->status_name == null)
					{{ $bedHelper->occupiedBy($bed->bed_code, $bed->ward_code) }}
					@else
					{{$bed->status_name}}
					@endif
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
