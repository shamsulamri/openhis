@extends('layouts.app')

@section('content')
<h1>Drug System List
<a href='/drug_systems/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/drug_system/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($drug_systems->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_systems as $drug_system)
	<tr>
			<td>
					<a href='{{ URL::to('drug_systems/'. $drug_system->system_code . '/edit') }}'>
						{{$drug_system->system_name}}
					</a>
			</td>
			<td>
					{{$drug_system->system_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_systems/delete/'. $drug_system->system_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_systems->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_systems->render() }}
@endif
<br>
@if ($drug_systems->total()>0)
	{{ $drug_systems->total() }} records found.
@else
	No record found.
@endif
@endsection
