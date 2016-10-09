@extends('layouts.app')

@section('content')
<h1>Medical Certificate List</h1>
<br>
<form action='/medical_certificate/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/medical_certificates/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($medical_certificates->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>encounter_id</th>
    <th>mc_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($medical_certificates as $medical_certificate)
	<tr>
			<td>
					<a href='{{ URL::to('medical_certificates/'. $medical_certificate->mc_id . '/edit') }}'>
						{{$medical_certificate->encounter_id}}
					</a>
			</td>
			<td>
					{{$medical_certificate->mc_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('medical_certificates/delete/'. $medical_certificate->mc_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $medical_certificates->appends(['search'=>$search])->render() }}
	@else
	{{ $medical_certificates->render() }}
@endif
<br>
@if ($medical_certificates->total()>0)
	{{ $medical_certificates->total() }} records found.
@else
	No record found.
@endif
@endsection
