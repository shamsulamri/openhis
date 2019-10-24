@extends('layouts.app2')

@section('content')
@if (!empty($purchase)) 
	@if ($purchase->document_code == 'goods_receive')
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Products</a>
	@else
		<!--
		<a class='btn btn-default btn-sm' href='/purchase_lines/master_item/{{ $purchase->purchase_id }}?reason=purchase'>Items</a>
		-->
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Products</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&type=reorder&purchase_id={{ $purchase->purchase_id }}'>Reorder</a>
	@endif
@else
	@if ($reason == 'request')
		<!--
		<a class='btn btn-default btn-sm' href='/inventory_movements/master_item/{{ $movement->move_id }}?reason=stock'>Items</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&type=purchase&move_id={{ $movement->move_id }}'>Purchase</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&type=indent&move_id={{ $movement->move_id }}'>Indent</a>
		-->
		<a class='btn btn-default btn-sm' href='/inventory_movements/master_document/{{ $movement->move_id }}?reason=stock'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=stock&move_id={{ $movement->move_id }}'>Products</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&move_id={{ $movement->move_id }}'>Request</a>
	@else
		<a class='btn btn-default btn-sm' href='/purchase_lines/master_item/{{ $movement->move_id }}?reason=stock'>Items</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=stock&move_id={{ $movement->move_id }}'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=stock&move_id={{ $movement->move_id }}'>Products</a> 
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&move_id={{ $movement->move_id }}'>Purchase</a>
	@endif
@endif
<br><br>
<form action='/purchase/master_search' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="{{ $find }}" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="reason" value="{{ $reason }}">
	<input type='hidden' name="id" value="{{ $id }}">
	<input type='hidden' name="type" value="{{ $type }}">
	@if ($reason == 'stock')
	<input type='hidden' name="move_id" value="{{ $id }}">
	@else
	<input type='hidden' name="purchase_id" value="{{ $id }}">
	@endif
</form>
<br>
@if ($purchases->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Document Number</th>
	<th></th>
	</tr>
  </thead>
	<tbody>
@foreach ($purchases as $line)
	<tr>
			<td>
					<a href='{{ URL::to('purchase_lines/master_item/'. $id.'/'.$line->purchase_id.'?reason='.$reason) }}'>
						{{$line->purchase_number}}
					</a>
			</td>
			<td>
					{{ DojoUtility::dateReadFormat($line->created_at) }}
			</td>
			<td align='right'>
					<a class='btn btn-primary btn-xs' href='{{ URL::to('purchase_lines/convert/'. $line->purchase_id.'/'.$id.'?reason='.$reason) }}'>
								<span class='glyphicon glyphicon-plus'></span>
					</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
{{ $purchases->appends(['search'=>$search, 'reason'=>$reason, 'move_id'=>$move_id, 'purchase_id'=>$purchase?$purchase->purchase_id:''])->render() }}

<!--
@if (isset($search)) 
	{{ $purchases->appends(['search'=>$search])->render() }}
	@else
	{{ $purchases->render() }}
@endif
-->
<br>
@if ($purchases->total()>0)
	{{ $purchases->total() }} records found.
@else
	No record found.
@endif

<script>
	var frameLine = parent.document.getElementById('frameLine');

	@if($reload=='true')
			@if($reason=='purchase')
					frameLine.src='/purchase_lines/detail/{{ $purchase->purchase_id }}';
			@else
					frameLine.src='/inventories/detail/{{ $movement->move_id }}';
			@endif
	@endif
</script>
@endsection
