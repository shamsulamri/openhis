@extends('layouts.app')

@section('content')
<h1>Bed Movement History</h1>
<form action='/bed_movement/enquiry' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Encounter id or MRN" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		&nbsp;
		<label>Movement</label>
		{{ Form::select('move_code', $movements, $move_code, ['class'=>'form-control']) }}
		<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
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
					{{$bed_movement->encounter->encounter_id}}
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($bed_movement->move_date)}}
			</td>
			<td>
					{{$bed_movement->encounter->patient->patient_name}}
			</td>
			<td>
					{{$bed_movement->encounter->patient->getMRN()}}
			</td>
			<td>
					{{$bed_movement->bedTo->bed_name}}
			</td>
			<td>
					@if ($bed_movement->move_from == $bed_movement->move_to) 
						Admission 
					@else
						@if ($bed_movement->bedFrom->ward_code == $bed_movement->bedTo->ward_code) 
								@if ($bed_movement->bedFrom->class_code == $bed_movement->bedTo->class_code) 
									Swap 
								@else
									Change
								@endif
						@else
								Transfer
						@endif
						
					@endif
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
@endsection
