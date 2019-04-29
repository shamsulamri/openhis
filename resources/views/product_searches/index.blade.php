@extends('layouts.app2')

@section('content')
<style>
.pagination {
    font-size: 80%;
}
</style>
@if (!empty($purchase)) 
	@if ($purchase->document_code == 'goods_receive')
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Products</a>
	@else
		<a class='btn btn-default btn-sm' href='/purchase_lines/master_item/{{ $purchase->purchase_id }}?reason=purchase'>Items</a>
		<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&purchase_id={{ $purchase->purchase_id }}'>Products</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=purchase&type=reorder&purchase_id={{ $purchase->purchase_id }}'>Reorder</a>
	@endif
<br><br>
@else
	@if (!empty($movement))
		<a class='btn btn-default btn-sm' href='/purchase_lines/master_item/{{ $movement->move_id }}?reason=stock'>Items</a>
		<a class='btn btn-default btn-sm' href='/inventory_movements/master_document/{{ $movement->move_id }}?reason=stock'>Documents</a>
		<a class='btn btn-default btn-sm' href='/product_searches?reason=stock&move_id={{ $movement->move_id }}'>Products</a>
		<br><br>
	@endif
@endif

<form action='/product_search/search' method='post'>
	<div class='input-group'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
	<input type='hidden' name="purchase_id" value="{{ $purchase_id }}">
	<input type='hidden' name="reason" value="{{ $reason }}">
	<input type='hidden' name="product_code" value="{{ $product_code }}">
	<input type='hidden' name="set_code" value="{{ $set_code }}">
	<input type='hidden' name="class_code" value="{{ $class_code }}">
	<input type='hidden' name="period_code" value="{{ $period_code }}">
	<input type='hidden' name="week" value="{{ $week }}">
	<input type='hidden' name="day" value="{{ $day }}">
	<input type='hidden' name="return_id" value="{{ $return_id }}">
	<input type='hidden' name="line_id" value="{{ $line_id }}">
	<input type='hidden' name="input_id" value="{{ $input_id }}">
	<input type='hidden' name="move_id" value="{{ $move_id }}">
	<input type='hidden' name="type" value="{{ $type }}">
</form>
<br>
@if ($product_searches->total()>0)
<table class="table table-condensed">
	<tbody>
@foreach ($product_searches as $product_search)
	<tr>
			<td>
					<a href='{{ URL::to('products/'. $product_search->product_code.'?reason='.$reason.'&id='.$return_id) }}' target='frameLine'>
						{{$product_search->product_name}}
						@if (!empty($product_search->stock_quantity))
							({{ $product_search->stock_quantity.'/'.$product_search->limit_min }})
						@endif
					</a>
					<br>
					{{ $product_search->product_code }}
			</td>
			<td align='right'>
				@if ($reason=='bom')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/bom/'. $product_code . '/' . $product_search->product_code) }}'>
				@elseif ($reason=='asset')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/asset/'. $set_code . '/' . $product_search->product_code) }}'>
				@elseif ($reason=='menu')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/menu/'. $class_code . '/' . $period_code . '/' . $week . '/'. $day . '/'. $product_search->product_code) .'/'. $diet_code }}'>
				@elseif ($reason=='bulk')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/bulk/'. $input_id . '/' . $product_search->product_code) }}'>
				@elseif ($reason=='stock')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('inventory_movements/add/'. $move_id . '/' . $product_search->product_code) }}'>
				@else
					<a class='btn btn-primary btn-xs' href='{{ URL::to('purchase_lines/add/'. $purchase_id . '/' . $product_search->product_code.'/'.$type) }}'>
				@endif
					<span class='glyphicon glyphicon-plus'></span></a>
			</td>
	</tr>
@endforeach
</tbody>
</table>
		@if ($type=='reorder')
		<a class='btn btn-default' href='/purchase_lines/add_reorder/{{ $purchase->purchase_id }}'>Add all reorder items</a>
		<br>
		@endif
@endif
@if (isset($search)) 
	{{ $product_searches->appends(['input_id'=>$input_id, 'diet_code'=>$diet_code,'class_code'=>$class_code, 'period_code'=>$period_code, 'week'=>$week, 'day'=>$day, 'set_code'=>$set_code, 'product_code'=>$product_code, 'search'=>$search,'reason'=>$reason,  'purchase_id'=>$purchase_id, 'move_id'=>$move_id])->render() }}
	@else
	{{ $product_searches->appends(['input_id'=>$input_id, 'diet_code'=>$diet_code,'class_code'=>$class_code, 'period_code'=>$period_code, 'week'=>$week, 'day'=>$day, 'set_code'=>$set_code, 'product_code'=>$product_code, 'reason'=>$reason, 'purchase_id'=>$purchase_id, 'move_id'=>$move_id])->render() }}
@endif
<br>
@if ($product_searches->total()>0)
	{{ $product_searches->total() }} records found.
@else
	No record found.
@endif

@if (Session::has('message'))
<script>
	var frameLine = parent.document.getElementById('frameLine');

	@if ($reason=='bom')
			frameLine.src='/bill_materials/index/{{ $product_code }}';
	@elseif ($reason=='asset')
			frameLine.src='/order_sets/index/{{ $set_code }}';
	@elseif ($reason=='menu')
			frameLine.src='/diet_menus/menu/{{ $class_code }}/{{ $period_code }}/{{ $week }}/{{ $day }}/{{ $diet_code }}';
	@elseif($reason=='bulk')
			frameLine.src='/stock_inputs/input/{{ $input_id }}';
	@elseif($reason=='stock')
			frameLine.src='/inventories/detail/{{ $move_id }}';
	@else
			frameLine.src='/purchase_lines/detail/{{ $purchase_id }}';
	@endif
</script>
@endif
@endsection
