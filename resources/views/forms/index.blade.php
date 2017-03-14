@extends('layouts.app')

@section('content')
<h1>Form List
<a href='/forms/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/form/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
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
					<a href='{{ URL::to('forms/'. $form->form_code.'/edit' ) }}'>
						{{$form->form_name}}
					</a>
			</td>
			<td>
					{{$form->form_code}}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('forms/'. $form->form_code) }}'>Assets</a>
					@can('system-administrator')
					<a class='btn btn-danger btn-xs' href='{{ URL::to('forms/delete/'. $form->form_code) }}'>Delete</a>
					@endcan
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
