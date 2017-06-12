@extends('layouts.app')

@section('content')
<h1>Admission Therapeutic Index
<a href='/admission_therapeutics/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/admission_therapeutic/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($admission_therapeutics->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>admission_id</th>
    <th>admission_id</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($admission_therapeutics as $admission_therapeutic)
	<tr>
			<td>
					<a href='{{ URL::to('admission_therapeutics/'. $admission_therapeutic->admission_id . '/edit') }}'>
						{{$admission_therapeutic->admission_id}}
					</a>
			</td>
			<td>
					{{$admission_therapeutic->admission_id}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('admission_therapeutics/delete/'. $admission_therapeutic->admission_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $admission_therapeutics->appends(['search'=>$search])->render() }}
	@else
	{{ $admission_therapeutics->render() }}
@endif
<br>
@if ($admission_therapeutics->total()>0)
	{{ $admission_therapeutics->total() }} records found.
@else
	No record found.
@endif
@endsection
