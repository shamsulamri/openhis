@extends('layouts.app')

@section('content')
<h1>Bill Index</h1>
<form action='/bill/search' method='post'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

<a href='/bills/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($bills->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Encounter Id</th> 
    <th>Bill Id</th> 
    <th>Patient</th> 
    <th>MRN</th> 
    <th>Grand Total</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bills as $bill)
	<tr>
			<td>
					{{$bill->encounter_id}}
			</td>
			<td>
					{{$bill->id}}
			</td>
			<td>
					{{$bill->encounter->patient->patient_name }}
			</td>
			<td>
					{{$bill->encounter->patient->patient_mrn }}
			</td>
			<td>
					<a href='{{ URL::to('bills/'. $bill->id . '/edit') }}'>
						{{$bill->bill_grand_total}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bills/delete/'. $bill->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bills->appends(['search'=>$search])->render() }}
	@else
	{{ $bills->render() }}
@endif
<br>
@if ($bills->total()>0)
	{{ $bills->total() }} records found.
@else
	No record found.
@endif
@endsection
