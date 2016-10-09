@extends('layouts.app')

@section('content')
<h1>Patient Billing Index</h1>
<br>
<form action='/patient_billing/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/patient_billings/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($patient_billings->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>encounter_id</th>
    <th>bill_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($patient_billings as $patient_billing)
	<tr>
			<td>
					<a href='{{ URL::to('patient_billings/'. $patient_billing->bill_id . '/edit') }}'>
						{{$patient_billing->encounter_id}}
					</a>
			</td>
			<td>
					{{$patient_billing->bill_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('patient_billings/delete/'. $patient_billing->bill_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $patient_billings->appends(['search'=>$search])->render() }}
	@else
	{{ $patient_billings->render() }}
@endif
<br>
@if ($patient_billings->total()>0)
	{{ $patient_billings->total() }} records found.
@else
	No record found.
@endif
@endsection
