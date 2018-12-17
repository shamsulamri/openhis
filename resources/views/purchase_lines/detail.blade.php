@extends('layouts.app2')

@section('content')
<?php 
$total_include_tax=0.0; 
$count=0;
if ($page>1) {
$count=($page-1)*10;
}
?>
@foreach ($purchase_lines as $purchase_line)
	<?php 
		if (empty($purchase_order_line->deleted_at)) {
			$total_include_tax += $purchase_line->line_subtotal_tax;
		}
	?>
@endforeach
	<div class="row">
			<div class="col-xs-6">
				<address>
				<strong>{{ $purchase->supplier->supplier_name }}</strong><br>
				{{ $purchase->supplier->supplier_street_1 }}
				<br>
				{{ $purchase->supplier->supplier_street_2 }}
				<br>
				{{ $purchase->supplier->supplier_city }}
				<br>
				{{ $purchase->supplier->supplier_postcode }} {{ $purchase->supplier->supplier_city }}
				<br>
				@if (!empty($pruchase_order->supplier->sate))
				{{ $purchase->supplier->state->state_name }}
				@endif
				</address>
			</div>
			<div class="col-xs-6">
				<div class='text-right'>
				<strong>Id:</strong> {{ $purchase->purchase_number }}<br>
				<strong>Date:</strong> {{ date('d/m/Y', strtotime(str_replace('/','-',$purchase->purchase_date))) }}
				</div>
			</div>
	</div>

@if ($purchase_lines->total()>0)
{{ Form::open(['url'=>'/purchase_lines/delete_selection/'.$purchase->purchase_id]) }}
<!--
{{ Form::submit('Stock Receive', ['class'=>'btn btn-primary']) }}
-->
<br>
<table class="table table-condensed">
 <thead>
	<tr> 
	<!--
    <th>#</th>
	-->
	<th>
		@if ($purchase->purchase_posted==0)
			<a class="btn btn-default btn-xs" href="javascript:toggleCheck()" role="button">
				<i class="fa fa-check"></i>
			</a>
		@endif
	</th>
    <th></th>
    <th>Item</th>
    <th><div align='right'>Quantity</div></th> 
    <th><div align='right'>Unit Price</div></th> 
    <th><div align='right'>Sub Total</div></th> 
    <th><div align='right'>Tax</div></th> 
    <th><div align='right'>Subtotal Include Tax</div></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_lines as $purchase_line)
	<?php 
		$count += 1;
	?>
	<tr>
			<td width='1'>
				@if ($purchase_line->line_posted == 0)
						@if ($purchase->purchase_posted==0)
							{{ Form::checkbox($purchase_line->line_id, 1, null,['id'=> $purchase_line->line_id, 'class'=>'i-checks']) }}
						@endif
				@endif
			</td>
			<td width='1'>
					{{ $count }}
			</td>
			<td>
			@if (empty($purchase_line->deleted_at))
					@if ($purchase->purchase_received==1)
						<strong>
						{{$purchase_line->product->product_name}}
						</strong>
					@else
							@if ($purchase->purchase_posted==0)
							<a href='{{ URL::to('purchase_lines/'. $purchase_line->line_id . '/edit') }}'>
								{{$purchase_line->product->product_name}}
							</a>
							@else
								{{$purchase_line->product->product_name}}
							@endif
					@endif
					<br>
					{{$purchase_line->product_code}}
			@else
					<s>
						{{$purchase_line->product->product_name}}
					</s>
			@endif
			</td>
			<td @if ($purchase->purchase_posted==0) width='80' @endif align='right'>
				@if ($purchase_line->line_quantity == 0) 
				<div class='has-error'>
        			<label class='control-label'>
				@endif
						{{ number_format($purchase_line->line_quantity) }} 
						@if ($purchase_line->unit_code != null)
							{{ $purchase_line->uom->unit_name }}
						@endif
				@if ($purchase_line->line_quantity == 0) 
					</label>
				@endif
				</div>
			</td>
			<td @if ($purchase->purchase_posted==0) width='50' @endif align='right'>
					{{ number_format($purchase_line->line_unit_price,2) }}
			</td>
			<td @if ($purchase->purchase_posted==0) width='10' @endif align='right'>
					{{ number_format($purchase_line->line_subtotal,2) }}
			</td>
			<td @if ($purchase->purchase_posted==0) width='10' @endif align='right'>
					{{ $purchase_line->tax_code }}
			</td>
			<td @if ($purchase->purchase_posted==0) width='50' @else width='100' @endif align='right'>
					{{ number_format($purchase_line->line_subtotal_tax,2) }}
			</td>
			<!--
			<td align='right' width='20'>
					@if ($purchase->purchase_received==0)
					@if (empty($purchase_line->deleted_at))
					<a class='btn btn-danger btn-xs' href='{{ URL::to('purchase_lines/delete/'. $purchase_line->line_id) }}'>-</a>
					@endif
					@endif
			</td>
			-->
	</tr>
@endforeach
@endif

@if ($total_include_tax>0)
	<tr>
		<td colspan='7' align='right'><strong>Total</strong></td>
		<td width='20' align='right'>{{ number_format($total_include_tax,2) }}</td>
	</tr>
@endif
</tbody>
</table>
<input type='hidden' name="_token" value="{{ csrf_token() }}">
@if (count($purchase_lines)>0 && $purchase->purchase_posted ==0)
		{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
@endif
{{ Form::close() }}
@if (isset($search)) 
	{{ $purchase_lines->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_lines->render() }}
@endif
<br>
@if ($purchase_lines->total()>0)
	<!--
	Total: {{ $purchase_lines->total() }} items
	-->
@else
	No record found.
@endif

<script>
	function toggleCheck(flag) {
		@foreach ($purchase_lines as $purchase_line)
			checked = $('#{{ $purchase_line->line_id }}').is(':checked');
			$('#{{ $purchase_line->line_id }}').prop('checked', !checked).iCheck('update');
		@endforeach
	}

	@if ($reload == 'true')
			var frameProduct = parent.document.getElementById('frameProduct');
			frameProduct.contentWindow.location.reload();
	@endif

</script>
@endsection
