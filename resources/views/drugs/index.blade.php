@extends('layouts.app')

@section('content')
<h1>Drug List
<a href='/drugs/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drugs->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th> 
    <th>Generic Name</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drugs as $drug)
	<tr>
			<td>
					{{$drug->drug_code}}
			</td>
			<td>
					<a href='{{ URL::to('drugs/'. $drug->drug_code . '/edit') }}'>
						{{$drug->drug_generic_name}}
					</a>
			</td>
			<td>
					{{$drug->trade_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drugs/delete/'. $drug->drug_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drugs->appends(['search'=>$search])->render() }}
	@else
	{{ $drugs->render() }}
@endif
<br>
@if ($drugs->total()>0)
	{{ $drugs->total() }} records found.
@else
	No record found.
@endif
@endsection
