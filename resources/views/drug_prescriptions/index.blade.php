@extends('layouts.app')

@section('content')
<h1>Drug Prescription List
<a href='/drug_prescriptions/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_prescription/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drug_prescriptions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Name</th>
    <th>Route</th>
    <th>Dosage</th>
    <th>Frequency</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_prescriptions as $drug_prescription)
	<tr>
			<td>
					{{$drug_prescription->drug_code}}
			</td>
			<td>
				<a href='{{ URL::to('drug_prescriptions/'. $drug_prescription->prescription_id . '/edit') }}'>
					{{$drug_prescription->product_name}}
				</a>
			</td>
			<td>
					{{ $drug_prescription->route_name }}
			</td>
			<td>
					{{$drug_prescription->drug_dosage}} {{ $drug_prescription->dosage_name }}
			</td>
			<td>
					{{ $drug_prescription->frequency_name }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_prescriptions/delete/'. $drug_prescription->prescription_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_prescriptions->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_prescriptions->render() }}
@endif
<br>
@if ($drug_prescriptions->total()>0)
	{{ $drug_prescriptions->total() }} records found.
@else
	No record found.
@endif
@endsection
