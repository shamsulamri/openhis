@extends('layouts.app2')

@section('content')
<form action='/dependant/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Enter patients name or identification" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<a href='/dependants/create/{{ $patient_id }}' class='btn btn-primary' target='frameDetail'><span class='fa fa-user-plus'></span></a>
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="patient_id" value="{{ $patient_id }}">
</form>
<br>
@if ($patients->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Identification</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($patients as $patient)
	<tr>
			<td>
					{{ strtoupper($patient->patient_name) }}
			</td>
			<td>
					{{$patient->patient_new_ic}}
			</td>
			<td align='right'>
					<a class='btn btn-primary btn-xs' href='/dependants/add/{{ $patient->patient_id }}/{{ $patient_id }}'><span class='fa fa-chevron-right'></span></a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patients->appends(['search'=>$search])->render() }}
	@else
	{{ $patients->render() }}
@endif
<br>
@if ($patients->total()>0)
	{{ $patients->total() }} records found.
@else
	No record found.
@endif
<script>
	var frame = parent.document.getElementById('frameDetail');
	frame.src = '/dependants?patient_id={{ $patient_id }}';
</script>
@endsection
