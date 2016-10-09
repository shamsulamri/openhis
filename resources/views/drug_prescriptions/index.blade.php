@extends('layouts.app')

@section('content')
<h1>Drug Prescription List</h1>
<br>
<form action='/drug_prescription/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/drug_prescriptions/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($drug_prescriptions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_prescriptions as $drug_prescription)
	<tr>
			<td>
					<a href='{{ URL::to('drug_prescriptions/'. $drug_prescription->prescription_id . '/edit') }}'>
						{{$drug_prescription->drug_code}}
					</a>
			</td>
			<td>
					{{$drug_prescription->prescription_id}}
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
