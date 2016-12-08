@extends('layouts.app')

@section('content')
<h1>Patient Flag List
<a href='/patient_flags/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/patient_flag/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($patient_flags->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($patient_flags as $patient_flag)
	<tr>
			<td>
					<a href='{{ URL::to('patient_flags/'. $patient_flag->flag_code . '/edit') }}'>
						{{$patient_flag->flag_name}}
					</a>
			</td>
			<td>
					{{$patient_flag->flag_code}}
			</td>
			<td align='right'>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('patient_flags/delete/'. $patient_flag->flag_code) }}'>Delete</a>
					@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patient_flags->appends(['search'=>$search])->render() }}
	@else
	{{ $patient_flags->render() }}
@endif
<br>
@if ($patient_flags->total()>0)
	{{ $patient_flags->total() }} records found.
@else
	No record found.
@endif
@endsection
