@extends('layouts.app2')

@section('content')
@if ($reason == 'purchase')
		<!--
		<a class='btn btn-default btn-sm' href='/purchase_lines/master_item/{{ $purchase->purchase_id }}?reason=purchase'>Items</a>
		-->
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Products</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&type=reorder&purchase_id={{ $purchase->purchase_id }}'>Reorder</a>
@else
		<!--
		<a class='btn btn-default btn-sm' href='/purchase_lines/master_item/{{ $movement->move_id }}?reason=stock'>Items</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&move_id={{ $movement->move_id }}'>Purchase</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&type=indent&move_id={{ $movement->move_id }}'>Indent</a>
		-->
		<a class='btn btn-default btn-sm' href='/inventory_movements/master_document/{{ $movement->move_id }}?reason=stock'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=stock&move_id={{ $movement->move_id }}'>Products</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&move_id={{ $movement->move_id }}'>Request</a>
@endif
<br>
<br>
<h4>Document: {{ $document->purchase_number }}</h4>
<!--
<form action='/purchase_line/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find in purchase items" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
			{{ Form::hidden('id', $id) }}
			{{ Form::hidden('document_id', $document_id) }}
			{{ Form::hidden('reason', $reason) }}
</form>
<br>
-->

@if ($purchase_lines->total()>0)
<form action='/purchase_lines/multiple' method='post'>
<table class="table table-hover">
 <thead>
	<tr> 
	<th>
			<a class="btn btn-default btn-xs" href="javascript:toggleCheck()" role="button">
				<i class="fa fa-check"></i>
			</a>
	</th>
    <th>Item</th> 
	<!--
    <th>Document</th> 
	-->
	<th><div align='right'>
		Quantity
	</div></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_lines as $purchase_line)
<?php
	$show_checkbox = true;
	$balance_quantity = $purchase_line->balanceQuantity();
	if ($balance_quantity==0 and $reason=='purchase') { $show_checkbox = false; }
	$show_checkbox = true;
?>
	<tr>
			<td width='10'>
				@if ($show_checkbox)
					{{ Form::checkbox($purchase_line->line_id, 1, null,['id'=>$purchase_line->line_id,'class'=>'i-checks']) }}
				@endif
			</td>
			<td>
						{{$purchase_line->product->product_name}}
					<br>
					{{$purchase_line->product_code}}
			</td>
			<!--
			<td width='10'>
					{{ $purchase_line->purchase_number }}
			</td>
			-->
			<td width='80' align='right'>
					@if ($reason == 'purchase')
						@if ($purchase_line->purchase->document_code == 'purchase_request')
							{{ number_format($purchase_line->line_quantity) }} 
						@else
							{{ number_format($purchase_line->line_quantity) }} 
							@if ($purchase_line->line_quantity-$helper->balanceQuantity($purchase_line->line_id)>0)
							({{ $purchase_line->line_quantity-$helper->balanceQuantity($purchase_line->line_id) }})
							@endif
						@endif
					@else
							{{ number_format($purchase_line->line_quantity) }} 
					@endif
					@if ($purchase_line->unit_code != null)
							{{ $purchase_line->uom->unit_name }}
					@endif

			</td>
	</tr>
@endforeach
</tbody>
</table>
			{{ Form::submit('Add', ['class'=>'btn btn-primary']) }}
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			{{ Form::hidden('id', $id) }}
			{{ Form::hidden('document_id', $document_id) }}
			{{ Form::hidden('reason', $reason) }}
</form>
@endif
@if (isset($search)) 
	{{ $purchase_lines->appends(['search'=>$search, 'id'=>$id, 'document_id'=>$document_id, 'reason'=>$reason])->render() }}
	@else
	{{ $purchase_lines->appends(['id'=>$id, 'document_id'=>$document_id, 'reason'=>$reason])->render() }}
@endif
<br>
@if ($purchase_lines->total()>0)
	{{ $purchase_lines->total() }} records found.
@else
	No record found.
@endif
<script>
	var frameLine = parent.document.getElementById('frameLine');

	@if($reload=='true')
			@if($reason=='stock' || $reason=='request')
					frameLine.src='/inventories/detail/{{ $movement->move_id }}';
			@else
					frameLine.src='/purchase_lines/detail/{{ $id }}';
			@endif		
	@endif

	function toggleCheck(flag) {
		//$('input').iCheck('check');
		@foreach ($purchase_lines as $purchase_line)
			checked = $('#{{ $purchase_line->line_id }}').is(':checked');
			$('#{{ $purchase_line->line_id }}').prop('checked', !checked).iCheck('update');
		@endforeach
	}
</script>
@endsection
