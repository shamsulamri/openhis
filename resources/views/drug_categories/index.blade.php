@extends('layouts.app')

@section('content')
<h1>Drug Category List</h1>
<br>
<form action='/drug_category/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/drug_categories/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($drug_categories->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($drug_categories as $drug_category)
	<tr>
			<td>
					<a href='{{ URL::to('drug_categories/'. $drug_category->category_code . '/edit') }}'>
						{{$drug_category->category_name}}
					</a>
			</td>
			<td>
					{{$drug_category->category_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('drug_categories/delete/'. $drug_category->category_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $drug_categories->appends(['search'=>$search])->render() }}
	@else
	{{ $drug_categories->render() }}
@endif
<br>
@if ($drug_categories->total()>0)
	{{ $drug_categories->total() }} records found.
@else
	No record found.
@endif
@endsection
