@extends('layouts.app')

@section('content')
<h1>Consultation Procedure Index</h1>
<br>
<form action='/consultation_procedure/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/consultation_procedures/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultation_procedures->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultation_procedures as $consultation_procedure)
	<tr>
			<td>
					<a href='{{ URL::to('consultation_procedures/'. $consultation_procedure->id . '/edit') }}'>
						{{$consultation_procedure->procedure_description}}
					</a>
			</td>
			<td>
					{{$consultation_procedure->id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('consultation_procedures/delete/'. $consultation_procedure->id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $consultation_procedures->appends(['search'=>$search])->render() }}
	@else
	{{ $consultation_procedures->render() }}
@endif
<br>
@if ($consultation_procedures->total()>0)
	{{ $consultation_procedures->total() }} records found.
@else
	No record found.
@endif
@endsection
