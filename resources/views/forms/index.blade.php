@extends('layouts.app')

@section('content')
<h1>Form List</h1>
<br>
<form action='/form/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/forms/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($forms->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($forms as $form)
	<tr>
			<td>
					<a href='{{ URL::to('forms/'. $form->form_code . '/edit') }}'>
						{{$form->form_name}}
					</a>
			</td>
			<td>
					{{$form->form_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('forms/delete/'. $form->form_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $forms->appends(['search'=>$search])->render() }}
	@else
	{{ $forms->render() }}
@endif
<br>
@if ($forms->total()>0)
	{{ $forms->total() }} records found.
@else
	No record found.
@endif
@endsection
