@extends('layouts.app')

@section('content')
<h1>General Ledger Index
<a href='/general_ledgers/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/general_ledger/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($general_ledgers->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Name</th>
    <th>Code</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($general_ledgers as $general_ledger)
	<tr>
			<td>
					<a href='{{ URL::to('general_ledgers/'. $general_ledger->gl_code . '/edit') }}'>
						{{$general_ledger->gl_name}}
					</a>
			</td>
			<td>
					{{$general_ledger->gl_code}}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('general_ledgers/delete/'. $general_ledger->gl_code) }}'>Delete</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $general_ledgers->appends(['search'=>$search])->render() }}
	@else
	{{ $general_ledgers->render() }}
@endif
<br>
@if ($general_ledgers->total()>0)
	{{ $general_ledgers->total() }} records found.
@else
	No record found.
@endif
@endsection
