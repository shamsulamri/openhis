@extends('layouts.app')

@section('content')
<h1>Drug Caution List
<a href='/drug_cautions/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_caution/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drug_cautions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Description</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_cautions as $drug_caution)
	<tr>
			<td>
						{{$drug_caution->caution_code}}
			</td>
			<td>
					<a href='{{ URL::to('drug_cautions/'. $drug_caution->caution_code . '/edit') }}'>
							{{$drug_caution->caution_english}}
					</a>
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_cautions/delete/'. $drug_caution->caution_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_cautions->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_cautions->render() }}
@endif
<br>
@if ($drug_cautions->total()>0)
	{{ $drug_cautions->total() }} records found.
@else
	No record found.
@endif
@endsection
