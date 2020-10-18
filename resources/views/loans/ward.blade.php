@extends('layouts.app')

@section('content')
<h1>Ward Request </h1>
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

@if ($loans->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Id</th>
    <th>Type</th>
    <th>Item</th>
    <th>Code</th>
    <th>Request</th>
    <th>Supply</th>
    <th>Request Date</th> 
    <th>Status</th> 
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
					@if ($loan->type)
					{{$loan->type->type_name}}
					@endif
			</td>
			<td>

					<a href='{{ URL::to('loans/request/'. $loan->loan_id . '/edit') }}'>
					@if ($loan->type_code=='folder')
					<span class='glyphicon glyphicon-folder-close' aria-hidden='true'></span>
						{{ $loan->getItemName() }}
					@else
					<span class='fa fa-glass' aria-hidden='true'></span>
						{{ $loan->product->product_name }}
					@endif
					</a>
			</td>
			<td>
					@if ($loan->product)
					{{ $loan->product->product_code }}
					@endif
			</td>
			<td>
					@if ($loan->type_code=='folder')
						-
					@else
						{{$loan->loan_quantity_request }}
					@endif
			</td>
			<td>
					@if ($loan->type_code=='folder')
						-
					@else
						@if ($loan->loan_code != 'request')
						{{$loan->loan_quantity }}
						@endif
					@endif
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($loan->created_at) }}
			</td>
			<td>
					{{$loan->status->loan_name}}
					@if ($loan->exchange_id>0)
						(Exchange)
					@endif
			</td>
			<td align='right'>
					@if ($loan->loan_code=='exchange' or $loan->loan_code=='request') 
					<a class='btn btn-danger btn-xs' href='{{ URL::to('loans/request/'. $loan->loan_id.'/delete') }}'>Delete</a>
					@endif
					@if ($loan->loan_code=='on_loan' && $loan->type_code!='folder') 
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
