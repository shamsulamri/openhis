@extends('layouts.app2')

@section('content')
<form id='form' action='/stock_input/save/{{ $input_id }}' method='post'>
@if ($stock_input->input_close==0)
{{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
<a href='/stock_input/indent/{{ $input_id }}' class='btn btn-default'>Indent</a>
<br>
<br>
@endif
<table class="table table-hover">
	<thead>
	<tr>
		<th>Product</th>
		<th width='50'>UOM</th>
		<th width='50'>On Hand</th>
		<th width='80'>Quantity</th>
		<th width='80'>Value</th>
		<th width='50'>Batch</th>
		@if ($stock_input->input_close==0)
		<th width='50'></th>
		@endif
	</tr>
	</thead>
	@foreach($input_lines as $line)
<?php
$class = 'default';
if ($line->product->product_track_batch==1) {
		$batch_count =  $stock_helper->getStockInputBatchCount($line->line_id);
		$batch_quantity = $line->line_quantity;
		if ($batch_count != $batch_quantity) $class = 'danger';
}
$on_hand = $stock_helper->getStockCountByStore($line->product_code, $stock_input->store_code);
?>
	<tr>
		<td>
			
			<strong>{{ $line->product->product_name }}</strong>
			<br>
			{{ $line->product_code }}
		</td>	
		<td align='center'>
			{{ $line->product->unitMeasure->unit_shortname }}
		</td>	
		<td align='center'>
			<div class='@if ($on_hand==0) has-error @endif'>
			{{ Form::label('on_hand_'.$line->line_id, $on_hand?:' 0 ', ['id'=>'on_hand_'.$line->line_id,'class'=>'form-control']) }}
			</div>
		</td>	
		<td>
		@if ($stock_input->input_close==0 && $on_hand>0)
			{{ Form::text('quantity_'.$line->line_id, $line->line_quantity, ['id'=>'quantity_'.$line->line_id,'class'=>'form-control', 'onchange'=>'updateValue('.$line->line_id.')']) }}
		@else
            {{ Form::label('quantity', $line->line_quantity?:' 0 ', ['class'=>'form-control']) }}
		@endif
		</td>	
		<td>
			{{ Form::hidden('average_'.$line->line_id, $line->product->product_average_cost, ['id'=>'average_'.$line->line_id]) }}
		@if ($stock_input->input_close==0 && $on_hand>0)
			{{ Form::text('value_'.$line->line_id, $line->line_value, ['class'=>'form-control','id'=>'value_'.$line->line_id]) }}
		@else
            {{ Form::label('value', $line->line_value?:' 0 ', ['class'=>'form-control']) }}
		@endif
		</td>	
		<td align='center'>
			<div class='@if ($class=='danger') has-error @endif'>
			@if ($line->product->product_track_batch==1 && $stock_input->input_close==0)
				@if ($line->line_quantity != 0 && $on_hand>0)
					<a href='{{ URL::to('stock_input_batches/batch/'.$line->line_id) }}'>
            		{{ Form::label('s', $batch_count."/".$batch_quantity, ['class'=>'form-control']) }}
					</a>
				@else
            		{{ Form::label('batch', '?', ['class'=>'form-control']) }}
				@endif
			@else
            		{{ Form::label('s', $batch_count."/".$batch_quantity, ['class'=>'form-control']) }}
			@endif
			</div>
		</td>	
		@if ($stock_input->input_close==0)
		<td align='right'>
			<a class='btn btn-danger btn-xs' href='{{ URL::to('stock_input_lines/delete/'. $line->line_id) }}'>
				<span class='glyphicon glyphicon-minus'></span>
			</a>
		</td>
		@endif
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
						document.getElementById('line_quantity').focus();	
						document.getElementById('line_quantity').disabled=false;
						document.getElementById('batch_number').disabled=false;
				} else {
						document.getElementById('line_quantity').disabled=true;
						document.getElementById('batch_number').disabled=true;
						document.getElementById('product_code').focus();	
				}
		}, 500);

		function updateValue(id) {
			product_value = document.getElementById('value_'+id)
			average_cost = document.getElementById('average_'+id).value;
			quantity = document.getElementById('quantity_'+id).value;
			product_value.value = Math.abs(average_cost*quantity);

		}
</script>
@endsection
