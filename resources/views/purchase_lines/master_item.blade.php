@extends('layouts.app2')

@section('content')
<a class='btn btn-default' href='/purchase_lines/master_item/{{ $purchase->purchase_id }}'>Items</a>
<a class='btn btn-default' href='/purchases/master_document/{{ $purchase->purchase_id }}'>Documents</a>
<a class='btn btn-default' href='/product_searches?reason={{ $purchase->purchase_document }}&purchase_id={{ $purchase->purchase_id }}'>Products</a>
<br>
<br>
<form action='/purchase_line/search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>

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
    <th>Document</th> 
	<th><div align='right'>Balance</div></th>
	<th>Subtotal</th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchase_lines as $purchase_line)
	<tr>
			<td width='10'>
					{{ Form::checkbox($purchase_line->line_id, 1, null,['id'=>$purchase_line->line_id,'class'=>'i-checks']) }}
			</td>
			<td>
						{{$purchase_line->product->product_name}}
					<br>
					{{$purchase_line->product_code}}
			</td>
			<td width='10'>
					{{ $purchase_line->purchase->purchase_number }}
			</td>
			<td width='80' align='right'>
					{{ number_format($purchase_line->balanceQuantity()) }} 
						@if ($purchase_line->unit_code != null)
							{{ $purchase_line->uom->unit_name }}
						@endif
			</td>
			<td width='10' align='right'>
					{{ number_format($purchase_line->line_subtotal_tax,2) }} 
			</td>
	</tr>
@endforeach
</tbody>
</table>
			{{ Form::submit('Add', ['class'=>'btn btn-primary']) }}
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			{{ Form::hidden('purchase_id', $purchase->purchase_id) }}
</form>
@endif
@if (isset($search)) 
	{{ $purchase_lines->appends(['search'=>$search])->render() }}
	@else
	{{ $purchase_lines->render() }}
@endif
<br>
@if ($purchase_lines->total()>0)
	{{ $purchase_lines->total() }} records found.
@else
	No record found.
@endif
<script>
	var frameLine = parent.document.getElementById('frameLine');
	frameLine.src='/purchase_lines/detail/{{ $purchase->purchase_id }}';

	function toggleCheck(flag) {
		//$('input').iCheck('check');
		@foreach ($purchase_lines as $purchase_line)
			checked = $('#{{ $purchase_line->line_id }}').is(':checked');
			$('#{{ $purchase_line->line_id }}').prop('checked', !checked).iCheck('update');
		@endforeach
	}
</script>
@endsection
