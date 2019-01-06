@extends('layouts.app')

@section('content')
<h1>Purchases
<a href='/purchases/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></span></a>
</h1>
<form action='/purchase/search' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'><div align='left'>Find</div></label>
						<div class='col-sm-9'>
						<input type='text' class='form-control' placeholder="Document Number" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label for='date_end' class='col-sm-3 control-label'>Type</label>
						<div class='col-sm-9'>
							{{ Form::select('document_code', $documents,$document_code?:'', ['class'=>'form-control','maxlength'=>'20']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<span class='input-group-btn'>
							<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
						</span>
					</div>
			</div>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<div class="row">
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Open</strong></h5>	
				<h2>
				<a href='/purchase/search?status_code=open'>{{ $purchase_helper->openPurchases() }}</a>
				</h2>
			</div>
		</div>
	</div>
@can('purchase_request')
	<div class="col-md-4">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Purchase Request</strong></h5>	
				<h4>
					<h2>
					<a href='/purchase/search?status_code=purchase_request'>{{ $purchase_helper->openPurchaseRequest() }}</a>
					</h2>
				</h4>	
			</div>
		</div>
	</div>
@endcan
</div>

@if ($purchases->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
	<th>Document Number</th>
	<th>Date</th> 
	<th>Type</th> 
	<th>Supplier</th> 
	<th>Store</th> 
	<th>Created By</th> 
	<th>Status</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchases as $purchase)
	<tr>
			<td>
					@if ($purchase->purchase_posted == 0)
					<a href='{{ URL::to('purchases/'. $purchase->purchase_id.'/edit') }}'>
						{{$purchase->purchase_number}}
					</a>
					@else
						{{$purchase->purchase_number}}
					@endif
			</td>
			<td width='10%'>
					{{ DojoUtility::dateReadFormat($purchase->created_at) }}
			</td>
			<td>
					{{$purchase->document->document_name }}
			</td>
			<td>
					{{$purchase->supplier ? $purchase->supplier->supplier_name : '-' }}
			</td>
			<td>
					{{$purchase->store?$purchase->store->store_name:'-' }}
			</td>
			<td>
					{{$purchase->user->name }}
			</td>
			<td>
			{{ $purchase->purchase_posted==1? 'Posted':'Open' }}
			{{ $purchase->purchaseRequestStatus ? ' ('.$purchase->purchaseRequestStatus->status_name.')' : '' }}
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('purchase_lines/show/'. $purchase->purchase_id) }}'>Line Item</a>
			@if ($author_id == $purchase->author_id)
					@if ($purchase->purchase_posted == 0)
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchases/delete/'. $purchase->purchase_id) }}'>Delete</a>
					@endif
			@endcan
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $purchases->appends(['search'=>$search])->render() }}
	@else
	{{ $purchases->render() }}
@endif
<br>
@if ($purchases->total()>0)
	{{ $purchases->total() }} records found.
@else
	No record found.
@endif
@endsection
