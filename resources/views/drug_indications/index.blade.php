@extends('layouts.app')

@section('content')
<h1>Drug Indication List
<a href='/drug_indications/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_indication/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drug_indications->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th> 
    <th>Description</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_indications as $drug_indication)
	<tr>
			<td>
					{{$drug_indication->indication_code}}
			</td>
			<td>
					<a href='{{ URL::to('drug_indications/'. $drug_indication->indication_code . '/edit') }}'>
						{{$drug_indication->indication_description}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_indications/delete/'. $drug_indication->indication_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_indications->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_indications->render() }}
@endif
<br>
@if ($drug_indications->total()>0)
	{{ $drug_indications->total() }} records found.
@else
	No record found.
@endif
@endsection
