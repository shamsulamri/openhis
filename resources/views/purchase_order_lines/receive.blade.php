@extends('layouts.app')

@section('content')
<h1>Stock Receive</h1>
{{ Form::open(['id'=>'myform','url'=>'/purchase_order_line/receive_post/'.$purchase_order->purchase_id, 'class'=>'form-horizontal']) }}
<br>
<br>
    <div class='form-group'>
        {{ Form::label('purchase_id', 'Purchase Id',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('purchase_id', $purchase_order->purchase_id, ['class'=>'form-control']) }}
        </div>
    </div>
    <div class='form-group'>
        {{ Form::label('supplier_name', 'Supplier',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('supplier_name', $purchase_order->supplier->supplier_name, ['class'=>'form-control']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-3 control-label'>Receiving Store<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			{{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
			@if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('invoice_number')) has-error @endif'>
        <label for='invoice_number' class='col-sm-3 control-label'>Invoice Number<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('invoice_number', null, ['class'=>'form-control']) }}
			@if ($errors->has('invoice_number')) <p class="help-block">{{ $errors->first('invoice_number') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('delivery_number')) has-error @endif'>
        <label for='delivery_number' class='col-sm-3 control-label'>Delivery Number</label>
        <div class='col-sm-9'>
            {{ Form::text('delivery_number', $purchase_order->delivery_number, ['class'=>'form-control']) }}
			@if ($errors->has('delivery_number')) <p class="help-block">{{ $errors->first('delivery_number') }}</p> @endif
        </div>
    </div>
<br>
<table class="table table-hover">
 <thead>
	<tr> 
    <th width='10'></th>
    <th>Product</th>
    <th width='100'>Quantity Order</th> 
    <th width='100'>Total Receive</th> 
    <th width='100'>Quantity Receive </th> 
    <th width='120'>Expiry Date</th> 
    <th width='150'>Batch Number</th> 
	</tr>
  </thead>
	<tbody>
@foreach($purchase_receives as $receive)
<?php
	$total_receive = $stock_helper->stockReceiveSum($receive->line_id);
	$balance = $receive->line_quantity_ordered-$total_receive;
?>
	<tr>
@if ($balance>0)
			<td>
					{{ Form::checkbox($receive->line_id,1, true) }}
			</td>
			<td>
				{{ $receive->product->product_name }} ({{ $receive->product->getUnitShortname() }})
				<br>
				<small>
				{{ $receive->product->product_code }}
				</small>
    			<div class='@if ($errors->has('line_'.$receive->line_id)) has-error @endif'>
				@if ($errors->has('line_'.$receive->line_id)) <p class="help-block">{{ $errors->first('line_'.$receive->line_id) }}</p> @endif
				</div>
			</td>
			<td>
            	{{ Form::label('supplier_name', number_format($receive->line_quantity_ordered), ['class'=>'form-control']) }}
			</td>
			<td>
				{{ Form::label('total_receive_'.$receive->line_id, $total_receive, ['class'=>'form-control']) }}
			</td>
			<td>
    			<div class='@if ($errors->has('line_'.$receive->line_id)) has-error @endif'>
				{{ Form::text('receive_quantity_'.$receive->line_id, number_format($receive->line_quantity_ordered)-$total_receive, ['class'=>'form-control']) }}
				</div>
			</td>
			<td>
				<div class="input-group date">
						<input data-mask="99/99/9999" name="expiry_date_{{ $receive->line_id }}" id="expiry_date_{{ $receive->line_id }}" type="text" class="form-control">
				</div>
			</td>
			<td>
				{{ Form::text('batch_number_'.$receive->line_id, null, ['class'=>'form-control']) }}
			</td>
@else
			<td>
			</td>
			<td>
				{{ $receive->product->product_name }} ({{ $receive->product->getUnitShortname() }})
				<br>
				<small>
				{{ $receive->product->product_code }}
				</small>
    			<div class='@if ($errors->has('line_'.$receive->line_id)) has-error @endif'>
				@if ($errors->has('line_'.$receive->line_id)) <p class="help-block">{{ $errors->first('line_'.$receive->line_id) }}</p> @endif
				</div>
			</td>
			<td>
            	{{ Form::label('supplier_name', number_format($receive->line_quantity_ordered), ['class'=>'form-control']) }}
			</td>
			<td>
				{{ Form::label('total_receive_'.$receive->line_id, $total_receive, ['class'=>'form-control']) }}
			</td>
			<td colspan='3'>
			</td>
@endif
	</tr>
@endforeach
</tbody>
</table>
    <div class="alert alert-warning">
	<table>
		<tr>
				<td width='30'>
					{{ Form::checkbox('close_purchase_order','1') }}
				</td>
				<td>
					<h3>Close this purchase order.
					<span style='color:red;'>
					<strong>Warning !</strong> this option is irrevisible.
					</span>
					</h3>
				</td>
		</tr>
	</table>
<br>
{{ Form::submit('Post Stock Receive', ['class'=>'btn btn-default']) }}
</div>
<script>

@foreach($purchase_receives as $receive)
		$('#expiry_date_{{ $receive->line_id }}').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
@endforeach

        $(document).ready(function(){
             $("#myform").validate({
                 rules: {
@foreach($purchase_receives as $receive)
                     receive_quantity_{{ $receive->line_id }}: {
                         number: true
                     },
@endforeach
				 }
             });
        });
</script>
{{ Form::close() }}
@endsection
