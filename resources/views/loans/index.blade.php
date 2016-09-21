@extends('layouts.app')

@section('content')
<h1>Loans</h1>
<br>
<form class='form-inline' action='/loan/search' method='post'>
	<label>Status</label>
	{{ Form::select('loan_code', $loan_status, $loan_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Ward</label>
	{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<input type='text' class='form-control' placeholder="Loan Id" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	{{ Form::submit('Refresh', ['class'=>'btn btn-default']) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	@if ($is_folder)
	<input type='hidden' name='type' value='folder'>
	@endif
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
	@if ($is_folder)
    <th>MRN</th>
	@else
    <th>Item</th>
    <th>Quantity</th> 
	@endif
    <th>Source</th> 
    <th>Request</th> 
    <th>Status</th> 
    <th>Closure</th> 
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
					<a href='{{ URL::to('loans/'. $loan->loan_id . '/edit') }}'>
						{{$loan->getItemName() }}
					</a>
			</td>
			@if (!$is_folder)
			<td>
					@if ($loan->loan_quantity>0)
					{{$loan->loan_quantity }}
					@else
					-
					@endif
			</td>
			@endif
			<td>
				@if (!empty($loan->ward_code))
					{{$loan->ward->ward_name }}
				@endif
				@if (!empty($loan->location_code))
					{{$loan->location->location_name }}
				@endif
			</td>
			<td>
					{{ date('d F Y', strtotime($loan->created_at )) }}
			</td>
			<td>
					{{$loan->status->loan_name}}
					@if ($loan->exchange_id>0)
						(Exchange)
					@endif
			</td>
			<td>
					@if ($loan->loan_code=='exchanged') 
						{{ date('d F Y', strtotime($loan->getLoanClosureDatetime() )) }}
					@endif
					@if ($loan->loan_code=='return')
							@if (!empty($loan->loan_closure_datetime))
								{{ date('d F Y', strtotime($loan->getLoanClosureDatetime() )) }}
							@endif
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
					<!--
					@if ($loan->loan_is_folder)
            		<a class="btn btn-default btn-xs" href="/documents?patient_mrn={{ $loan->item_code }}" role="button">Documents</a>
					@endif
					-->
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
