@extends('layouts.app')

@section('content')
<h1>Loans</h1>
<br>
<form class='form-inline' action='/loan/index' method='post'>
	<label>Status</label>
	{{ Form::select('loan_code', $loan_status, $loan_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<label>Ward</label>
	{{ Form::select('ward_code', $wards, $ward_code, ['class'=>'form-control','maxlength'=>'10']) }}
	<input type='text' class='form-control' placeholder="Item code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
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
    <th>Item</th>
	@if (!$is_folder)
    <th>Quantity</th> 
	@endif
    <th>Ward</th> 
    <th>Request</th> 
    <th>Status</th> 
    <th>Date</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($loans as $loan)
	<tr>
			<td>
					<a href='{{ URL::to('loans/'. $loan->loan_id . '/edit') }}'>
						{{$loan->getItemName() }}
					</a>
			</td>
			@if (!$is_folder)
			<td>
					{{$loan->loan_quantity }}
			</td>
			@endif
			<td>
					{{$loan->ward->ward_name }}
			</td>
			<td>
					{{ date('d F Y', strtotime($loan->created_at )) }}
			</td>
			<td>
					{{$loan->status->loan_name}}
			</td>
			<td>
					@if ($loan->loan_code=='exchanged') 
						{{ date('d F Y', strtotime($loan->getLoanReturn() )) }}
					@endif
					@if ($loan->loan_code=='return')
							@if (!empty($loan->loan_return))
								{{ date('d F Y', strtotime($loan->getLoanReturn() )) }}
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
					@if ($loan->loan_is_folder)
            		<a class="btn btn-default btn-xs" href="/documents?patient_mrn={{ $loan->item_code }}" role="button">View Documents</a>
					@endif
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
