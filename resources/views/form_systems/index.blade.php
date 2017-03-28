@extends('layouts.app')

@section('content')
<h1>Form System Index</h1>
<br>
<form action='/form_system/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/form_systems/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($form_systems->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>system_name</th>
    <th>system_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($form_systems as $form_system)
	<tr>
			<td>
					<a href='{{ URL::to('form_systems/'. $form_system->system_code . '/edit') }}'>
						{{$form_system->system_name}}
					</a>
			</td>
			<td>
					{{$form_system->system_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('form_systems/delete/'. $form_system->system_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $form_systems->appends(['search'=>$search])->render() }}
	@else
	{{ $form_systems->render() }}
@endif
<br>
@if ($form_systems->total()>0)
	{{ $form_systems->total() }} records found.
@else
	No record found.
@endif
@endsection
