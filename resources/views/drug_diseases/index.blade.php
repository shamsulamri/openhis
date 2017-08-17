@extends('layouts.app')

@section('content')
<h1>Drug Disease Index
<a href='/drug_diseases/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_disease/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drug_diseases->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>drug_code</th>
    <th>disease_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_diseases as $drug_disease)
	<tr>
			<td>
					<a href='{{ URL::to('drug_diseases/'. $drug_disease->disease_id . '/edit') }}'>
						{{$drug_disease->drug_code}}
					</a>
			</td>
			<td>
					{{$drug_disease->disease_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_diseases/delete/'. $drug_disease->disease_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_diseases->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_diseases->render() }}
@endif
<br>
@if ($drug_diseases->total()>0)
	{{ $drug_diseases->total() }} records found.
@else
	No record found.
@endif
@endsection
