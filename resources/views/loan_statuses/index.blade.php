@extends('layouts.app')

@section('content')
<h1>Loan Status Index</h1>
<br>
<form action='/loan_status/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<a href='/loan_statuses/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($loan_statuses->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Code</th>
    <th>Name</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($loan_statuses as $loan_status)
	<tr>
			<td>
					<a href='{{ URL::to('loan_statuses/'. $loan_status->loan_code . '/edit') }}'>
						{{$loan_status->loan_code}}
					</a>
			</td>
			<td>
					{{$loan_status->loan_name}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('loan_statuses/delete/'. $loan_status->loan_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $loan_statuses->appends(['search'=>$search])->render() }}
	@else
	{{ $loan_statuses->render() }}
@endif
<br>
@if ($loan_statuses->total()>0)
	{{ $loan_statuses->total() }} records found.
@else
	No record found.
@endif
@endsection
