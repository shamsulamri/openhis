@extends('layouts.app')

@section('content')
<h1>Drug System List</h1>
<br>
<form action='/drug_system/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/drug_systems/create' class='btn btn-primary'>Create</a>
<br>
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
