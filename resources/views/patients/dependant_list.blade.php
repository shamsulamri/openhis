@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>Dependant List
</h1>
<a href='{{ URL::to('patients/dependants/'. $patient->patient_id) }}' class='btn btn-primary'>
Create
</a>
@if (count($patients)>0)

<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>MRN</th> 
    <th>Home Phone</th> 
    <th>Mobile Phone</th> 
    <th></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($patients as $p)
	<tr>
		<td>
			{{ $p->patient_name }}
		</td>
		<td>
			{{ $p->patient_mrn }}
		</td>
		<td>
			{{ $p->patient_phone_home }}
		</td>
		<td>
			{{ $p->patient_phone_mobile }}
		</td>
		<td>
			<a class='btn btn-default btn-xs pull-right' href='/patients/{{ $p->patient_id }}/edit'>Swap</a>
		</td>
	</tr>
@endforeach
</tbody>
</table>
@endif

@endsection
