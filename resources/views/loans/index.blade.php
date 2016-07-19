@extends('layouts.app')

@section('content')
<h1>Loan Index</h1>
<br>
<form action='/loan/search' method='post'>
	<input type='text' class='form-control input-lg' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<br>
<a href='/loans/create' class='btn btn-primary'>Create</a>
<br>
<br>
@if ($loans->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>item_code</th>
    <th>Ward</th> 
    <th>Status</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($loans as $loan)
	<tr>
			<td>
					<a href='{{ URL::to('loans/'. $loan->loan_id . '/edit') }}'>
						{{$loan->item_code}}
					</a>
			</td>
			<td>
					{{$loan->ward_code}}
			</td>
			<td>
					{{$loan->loan_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('loans/delete/'. $loan->loan_id) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $loans->appends(['search'=>$search])->render() }}
	@else
	{{ $loans->render() }}
@endif
<br>
@if ($loans->total()>0)
	{{ $loans->total() }} records found.
@else
	No record found.
@endif
@endsection
