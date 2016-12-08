@extends('layouts.app')

@section('content')
<h1>Drug Dosage List
<a href='/drug_dosages/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_dosage/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
