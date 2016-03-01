@extends('layouts.app')

@section('content')
<h1>Consultation Index</h1>
<br>
<form action='/consultation/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/consultations/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($consultations->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($consultations as $consultation)
	<tr>
			<td>
					<a href='{{ URL::to('consultations/'. $consultation->consultation_id . '/edit') }}'>
						{{$consultation->consultation_notes}}
					</a>
			</td>
			<td>
					{{$consultation->consultation_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('consultations/delete/'. $consultation->consultation_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $consultations->appends(['search'=>$search])->render() }}
	@else
	{{ $consultations->render() }}
@endif
<br>
@if ($consultations->total()>0)
	{{ $consultations->total() }} records found.
@else
	No record found.
@endif
@endsection
