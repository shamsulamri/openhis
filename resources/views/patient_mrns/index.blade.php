@extends('layouts.app')

@section('content')
<h1>Patient Mrn Index
<a href='/patient_mrns/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/patient_mrn/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($patient_mrns->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>mrn_id</th>
    <th>mrn_id</th> 
	@can('system-administrator')
	<th></th>
	@endcan
	</tr>
  </thead>
	<tbody>
@foreach ($patient_mrns as $patient_mrn)
	<tr>
			<td>
					<a href='{{ URL::to('patient_mrns/'. $patient_mrn->mrn_id . '/edit') }}'>
						{{$patient_mrn->mrn_id}}
					</a>
			</td>
			<td>
					{{$patient_mrn->mrn_id}}
			</td>
			@can('system-administrator')
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('patient_mrns/delete/'. $patient_mrn->mrn_id) }}'>Delete</a>
			</td>
			@endcan
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patient_mrns->appends(['search'=>$search])->render() }}
	@else
	{{ $patient_mrns->render() }}
@endif
<br>
@if ($patient_mrns->total()>0)
	{{ $patient_mrns->total() }} records found.
@else
	No record found.
@endif
@endsection
