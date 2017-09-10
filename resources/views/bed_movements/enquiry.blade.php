@extends('layouts.app')

@section('content')
<h1>Bed Movement History</h1>
<form id='form' action='/bed_movement/enquiry' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Encounter id or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		&nbsp;
		<label>Movement</label>
		{{ Form::select('move_code', $movements, $move_code, ['class'=>'form-control']) }}
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
		<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($bed_movements->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter Id</th> 
    <th>Movement Date</th> 
    <th>Patient</th> 
    <th>MRN</th>
    <th>Bed</th> 
    <th>Movement</th> 
	</tr>
  </thead>
	<tbody>
@foreach ($bed_movements as $bed_movement)
	<tr>
			<td>
					{{$bed_movement->encounter_id}}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($bed_movement->move_date)}}
			</td>
			<td>
					{{$bed_movement->patient_name}}
			</td>
			<td>
					{{$bed_movement->patient_mrn}}
			</td>
			<td>
					{{$bed_movement->bed_to}}
			</td>
			<td>
					{{ $bed_movement->transaction_name }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bed_movements->appends(['search'=>$search])->render() }}
	@else
	{{ $bed_movements->render() }}
@endif
<br>
@if ($bed_movements->total()>0)
	{{ $bed_movements->total() }} records found.
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
