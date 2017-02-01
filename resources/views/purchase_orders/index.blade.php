@extends('layouts.app')

@section('content')
<h1>Purchase Orders</h1>
<div class="row">
	<div class="col-md-6">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Open</strong></h5>	
				<h4><strong>{{ $poHelper->open() }}</strong></h4>	
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class='panel panel-default'>
			<div class='panel-body' align='middle'>
				<h5><strong>Posted</strong></h5>	
				<h4><strong>{{ $poHelper->posted() }}</strong></h4>	
			</div>
		</div>
	</div>
</div>
<form action='/purchase_order/search' method='post' class='form-inline'>
	<input type='text' class='form-control' placeholder="Purchase ID" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
	{{ Form::select('status', $statuses, $status, ['class'=>'form-control']) }}
	{{ Form::select('supplier_code', $supplier, $supplier_code, ['class'=>'form-control']) }}
	<div class="input-group date">
		<input data-mask="99/99/9999" name="date_search" id="date_search" type="text" class="form-control" value="{{ $date_search }}">
		<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
	</div>
	<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
	<a href='/purchase_orders/create' class='btn btn-primary pull-right'><span class='glyphicon glyphicon-plus'></a>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@if ($purchase_orders->total()>0)
<form action='/purchase_order/posts' method='post'>
<table class="table table-hover">
 <thead>
	<tr> 
    <th width='10'></th>
    <th>Date</th>
    <th>Supplier</th> 
    <th>Status</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_orders as $purchase_order)
	<tr>
			<td>
				@if (!$purchase_order->purchase_posted)
					{{ Form::checkbox($purchase_order->purchase_id, 1, null) }}
				@endif
			</td>
			<td>
					{{ date('d F Y', strtotime($purchase_order->purchase_date)) }}
			</td>
			<td>
					{{$purchase_order->supplier_name}}
			</td>
			<td>
					@if ($purchase_order->purchase_posted==1)
							@if ($purchase_order->purchase_received==1)
							<div class='label label-success'>
								Stock Receive
							</div>
							@else
							<div class='label label-warning'>
								Posted
							</div>
							@endif
					@else
					<div class='label label-default'>
						&nbsp;Open&nbsp;
					</div>
					@endif
			</td>
			<td align='right'>
					<a class='btn btn-default btn-xs' href='{{ URL::to('purchase_order_lines/'. $purchase_order->purchase_id) }}'>Line Items</a>
				@if (!$purchase_order->purchase_posted)
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_orders/delete/'. $purchase_order->purchase_id) }}'>Delete</a>
				@endif
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if ($purchase_orders->total()>0)
		{{ Form::submit('Post Selection', ['class'=>'btn btn-warning btn-xs']) }}
		<input type='hidden' name="_token" value="{{ csrf_token() }}">
@endif
</form>
@if (isset($search)) 
	{{ $purchase_orders->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_orders->render() }}
@endif
<br>
@if ($purchase_orders->total()>0)
	{{ $purchase_orders->total() }} records found.
@else
	No record found.
@endif
	<script>
		$('#date_search').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	</script>
@endsection
