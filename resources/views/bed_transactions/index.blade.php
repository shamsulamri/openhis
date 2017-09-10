@extends('layouts.app')

@section('content')
<h1>Bed Transaction Index
<a href='/bed_transactions/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/bed_transaction/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($bed_transactions->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>transaction_name</th>
    <th>transaction_code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($bed_transactions as $bed_transaction)
	<tr>
			<td>
					<a href='{{ URL::to('bed_transactions/'. $bed_transaction->transaction_code . '/edit') }}'>
						{{$bed_transaction->transaction_name}}
					</a>
			</td>
			<td>
					{{$bed_transaction->transaction_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('bed_transactions/delete/'. $bed_transaction->transaction_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $bed_transactions->appends(['search'=>$search])->render() }}
	@else
	{{ $bed_transactions->render() }}
@endif
<br>
@if ($bed_transactions->total()>0)
	{{ $bed_transactions->total() }} records found.
@else
	No record found.
@endif
@endsection
