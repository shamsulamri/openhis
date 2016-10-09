@extends('layouts.app')

@section('content')
<h1>Diet Complain List</h1>
<br>
<form action='/diet_complain/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/diet_complains/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($diet_complains->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Patient MRN</th>
    <th>Ward</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($diet_complains as $diet_complain)
	<tr>
			<td>
					{{ date('d F Y', strtotime($diet_complain->complain_date)) }}
			</td>
			<td>
					<a href='{{ URL::to('diet_complains/'. $diet_complain->complain_id . '/edit') }}'>
						{{$diet_complain->patient_mrn}}
					</a>
			</td>
			<td>
					{{$diet_complain->ward_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('diet_complains/delete/'. $diet_complain->complain_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $diet_complains->appends(['search'=>$search])->render() }}
	@else
	{{ $diet_complains->render() }}
@endif
<br>
@if ($diet_complains->total()>0)
	{{ $diet_complains->total() }} records found.
@else
	No record found.
@endif
@endsection
