@extends('layouts.app2')

@section('content')
<form id='form' action='/stock_input/input' method='post'>
<table class="table table-hover">
	<thead>
	<tr>
		<th>Product</th>
		<th width='15%'><div align='right'>Batch Number<div></th>
		<th width='15%'><div align='right'>Quantity On Hand<div></th>
		<th width='15%'><div align='right'>New Quantity<div></th>
		<th><div align='right'><div></th>
	</tr>
	</thead>
	<!--
	<tr class='info'>
		<td valign='middle'>
			@if ($product)
            {{ Form::text('product_code', $product->product_code, ['id'=>'product_code','class'=>'form-control','placeholder'=>'Enter product code ','onkeypress'=>'handle(event)','maxlength'=>'100']) }}
			@else
            {{ Form::text('product_code', null, ['id'=>'product_code','class'=>'form-control','placeholder'=>'Enter product code ','onkeypress'=>'handle(event)','maxlength'=>'100']) }}
			@endif
		</td>
		<td>
			@if (!empty($product->product_name))
			{{ Form::label('label', $product->product_name, ['class'=>'control-label']) }}
			@endif
		</td>
		<td>
            {{ Form::text('batch_number', null, ['id'=>'batch_number', 'class'=>'form-control','maxlength'=>'100', 'onkeypress'=>'handle(event)']) }}
		</td>
		<td align='right'>
			@if (!empty($stock_store->stock_quantity))
			{{ Form::label('label', number_format($stock_store->stock_quantity), ['class'=>'control-label']) }}
			@else
			{{ Form::label('label', '-', ['class'=>'control-label']) }}
			@endif
		</td>
		<td>
            {{ Form::text('amount_new', null, ['id'=>'amount_new', 'class'=>'form-control','placeholder'=>'Enter amount','maxlength'=>'100', 'onkeypress'=>'handle(event)']) }}
			</div>
		</td>
	</tr>
	-->
	@foreach($input_lines as $line)
	<tr>
		<td>
			
			<a href='{{ URL::to('stock_input_lines/'. $line->line_id . '/edit') }}'>
			{{ $line->product->product_name }}
			</a>
			<br>
			{{ $line->product_code }}
		</td>	
		<td align='right'>
			{{ $line->batch_number }}
		</td>	
		<td align='right'>
			{{ Form::label('label', $stock_helper->getStockCountByStore($line->product_code, $stock_input->store_code), ['class'=>'control-label']) }}
		</td>	
		<td align='right'>
			{{ $line->amount_new }}
		</td>	
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_input_lines/delete/'. $line->line_id) }}'>
											<span class='glyphicon glyphicon-remove'></span>
					</a>
			</td>
	</tr>
	@endforeach
</table>

	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	{{ Form::hidden('input_id', $stock_input->input_id) }}
</form>

<script>
		function updateInput() {
				document.getElementById('form').submit();
		}

		function handle(e){
				if(e.keyCode === 13){
						e.preventDefault(); // Ensure it is only this code that rusn
						document.getElementById('form').submit();
				}
        }

		setTimeout(function(){
				if (document.getElementById('product_code').value) {
						document.getElementById('amount_new').focus();	
						document.getElementById('amount_new').disabled=false;
						document.getElementById('batch_number').disabled=false;
				} else {
						document.getElementById('amount_new').disabled=true;
						document.getElementById('batch_number').disabled=true;
						document.getElementById('product_code').focus();	
				}
		}, 500);

</script>
@endsection
