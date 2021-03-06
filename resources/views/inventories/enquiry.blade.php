@extends('layouts.app')

@section('content')
<h1>Stock Movements Enquiry</h1>
<form id='form' action='/inventory/enquiry' method='post' class='form-horizontal'>
	<div class="row">
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Product</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' placeholder="Name or code" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'>Store</label>
						<div class='col-sm-9'>
							{{ Form::select('store_code', $store, $store_code, ['class'=>'form-control','maxlength'=>'10']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-4">
					<div class='form-group'>
						<label class='col-sm-3 control-label'><div align='left'>Batch</div></label>
						<div class='col-sm-9'>
							<input type='text' class='form-control' name='batch_number' value='{{ isset($batch_number) ? $batch_number : '' }}' autocomplete='off' autofocus>
						</div>
					</div>
			</div>
	</div>
	<a href='#' onclick='javascript:search_now(0);' class='btn btn-primary'>Search</a>
	<a href='#' onclick='javascript:search_now(1);' class='btn btn-primary pull-right'><span class='fa fa-print'></span></a>
	<input type='hidden' id='export_report' name="export_report">
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

@if ($inventories->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Date</th> 
    <th>Movement</th>
    <th>Number</th>
    <th>Code</th>
    <th>Product</th>
    <th>Store</th>
    <th>Batch</th>
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Unit</div></th> 
    <th><div align='right'>Unit Cost</div></th> 
    <th><div align='right'>Total</div></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($inventories as $inventory)
	<tr>
			<td>
					{{ DojoUtility::dateReadFormat($inventory->inv_datetime) }}
			</td>
			<td>
				@if ($inventory->move_code == 'sale')
					@if (!empty($inventory->origin_encounter))
					<a href='{{ URL::to('order/enquiry?encounter_id='. $inventory->origin_encounter.'&product_code='.$inventory->product_code) }}'>
					@else
					<a href='{{ URL::to('order/enquiry?encounter_id='. $inventory->replicate_encounter.'&product_code='.$inventory->product_code) }}'>
					@endif
						{{ $inventory->move_name }}
					</a>
				@else
						{{ $inventory->move_name }}
				@endif
				@if (!empty($inventory->move_description))
				 ({{ $inventory->move_description }})
				@endif
			</td>
			<td>
			@if ($inventory->move_number)
				<a href = '{{ URL::to('inventory_movements/show/'.$inventory->move_id) }}'>
				{{ $inventory->move_number }}
				</a>
			@else
				<a href = '{{ URL::to('purchase_lines/show/'.$inventory->purchase_id) }}'>
				{{ $inventory->purchase_number }}
				</a>
			@endif
			</td>
			<td>
				{{ $inventory->product_code }}
			</td>
			<td>
				<a href='{{ URL::to('inventory/enquiry?search='. $inventory->product_code.'&store_code='.$store_code) }}'>
				{{ $inventory->product->product_name }}
				</a>
			</td>
			<td>
				{{ $inventory->store_name }}
			</td>
			<td>
					{{ $inventory->inv_batch_number?:'-' }}
			</td>
			<td align='right'>
			@if ($inventory->inv_quantity<0)
					({{ abs($inventory->inv_quantity) }})
			@else
					@if ($inventory->unit_code != 'unit' && $inventory->move_code != 'sale')
							{{ abs($inventory->inv_quantity) }}
					@else
							{{ abs($inventory->inv_physical_quantity) }}
					@endif
			@endif
			</td>
			<td align='right'>
				@if ($inventory->unit_code != 'unit' && $inventory->move_code != 'sale')
					{{ $inventory->inv_physical_quantity }}	
				@endif
					{{ $inventory->unit_name }}
			</td>
			<td align='right'>
					{{ number_format(abs($inventory->inv_unit_cost),2) }}
			</td>
			<td align='right'>
					{{ number_format(abs($inventory->inv_subtotal),2) }}
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $inventories->appends(['search'=>$search,'batch_number'=>$batch_number, 'store_code'=>$store_code])->render() }}
<br>
@if ($inventories->total()>0)
	{{ $inventories->total() }} records found.
@else
	No record found.
@endif
<script>
		function search_now(value) {
				document.getElementById('export_report').value = value;
				document.getElementById('form').submit();
		}
</script>
@endsection
