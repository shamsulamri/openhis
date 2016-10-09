@extends('layouts.app')

@section('content')
<h1>Drug Dosage List</h1>
<br>
<form action='/drug_dosage/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/drug_dosages/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($drug_dosages->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_dosages as $drug_dosage)
	<tr>
			<td>
					<a href='{{ URL::to('drug_dosages/'. $drug_dosage->dosage_code . '/edit') }}'>
						{{$drug_dosage->dosage_name}}
					</a>
			</td>
			<td>
					{{$drug_dosage->dosage_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_dosages/delete/'. $drug_dosage->dosage_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_dosages->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_dosages->render() }}
@endif
<br>
@if ($drug_dosages->total()>0)
	{{ $drug_dosages->total() }} records found.
@else
	No record found.
@endif
@endsection
