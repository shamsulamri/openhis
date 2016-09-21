@extends('layouts.app')

@section('content')
<h1>Loans</h1>
@if (!empty($ward_code))
<h3>{{ $ward->ward_name }}</h3>
@endif
<br>
<form class='form-inline' action='/loan/request_search' method='post'>
	<label>Status</label>
	{{ Form::select('loan_code', $loan_status, $loan_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<input type='text' class='form-control' placeholder="Item code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	{{ Form::submit('Refresh', ['class'=>'btn btn-default']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
@if ($loans->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Id</th>
    <th>Item</th>
    <th>Quantity</th>
    <th>Status</th> 
    <th>Date</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($loans as $loan)
	<tr>
			<td>
					{{ $loan->loan_id }}
			</td>
			<td>
					@if ($loan->loan_is_folder)
					<span class='glyphicon glyphicon-folder-close' aria-hidden='true'></span>
					@else
					<span class='glyphicon glyphicon-shopping-cart' aria-hidden='true'></span>
					@endif
					<a href='{{ URL::to('loans/request/'. $loan->loan_id . '/edit') }}'>
						{{ $loan->getItemName() }}
					</a>
			</td>
			<td>
					@if ($loan->loan_is_folder)
						-
					@else
						{{$loan->loan_quantity }}
					@endif
			</td>
			<td>
					{{$loan->status->loan_name}}
			</td>
			<td>
					@if ($loan->loan_code=='exchanged') 
						{{ date('d F Y', strtotime($loan->getLoanClosureDateTime() )) }}
					@endif
					@if ($loan->loan_code=='exchange') 
						{{ date('d F Y', strtotime($loan->created_at )) }}
					@endif
					@if ($loan->loan_code=='return')
							@if (!empty($loan->loan_closure_datetime))
								{{ date('d F Y', strtotime($loan->getLoanClosureDateTime() )) }}
							@endif
					@endif
					@if ($loan->loan_code=='request')
						{{ date('d F Y', strtotime($loan->created_at )) }}
					@endif
					@if ($loan->loan_code=='lend')
							@if (!empty($loan->loan_date_start))
							{{ date('d F Y', strtotime($loan->getLoanDateStart() )) }}
							@endif
							@if (!empty($loan->loan_date_end))
							- {{ date('d F Y', strtotime($loan->getLoanDateEnd() )) }}
							@endif
					@endif
			</td>
			<td align='right'>
					@if ($loan->loan_code=='exchange' or $loan->loan_code=='request') 
					<a class='btn btn-danger btn-xs' href='{{ URL::to('loans/request/'. $loan->loan_id.'/delete') }}'>Delete</a>
					@endif
					@if ($loan->loan_code=='lend' && !$loan->loan_is_folder) 
					<a class='btn btn-default btn-xs' href="{{ URL::to('loans/request/'. $loan->loan_id.'/?loan=exchange') }}">Exchange</a>
					@endif
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
