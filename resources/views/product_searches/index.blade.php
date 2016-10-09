@extends('layouts.app2')

@section('content')
<style>
.pagination {
    font-size: 60%;
}
</style>
<br>
<form action='/product_search/search' method='post'>
	<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
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
</form>
<br>
@if (Session::has('message'))
	<br>
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@else
	<br>
@endif
@if ($product_searches->total()>0)
<table class="table table-condensed">
	<tbody>
@foreach ($product_searches as $product_search)
	<tr>
			<td>
					<a href='{{ URL::to('products/'. $product_search->product_code.'?reason='.$reason.'&id='.$return_id) }}' target='frameLine'>
						{{$product_search->product_name}}
					</a>
			</td>
			<td align='right'>
				@if ($reason=='purchase_order')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/add/'. $purchase_id . '/' . $product_search->product_code) }}'>+</a>
				@endif
				@if ($reason=='bom')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/bom/'. $product_code . '/' . $product_search->product_code) }}'>+</a>
				@endif
				@if ($reason=='asset')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/asset/'. $set_code . '/' . $product_search->product_code) }}'>+</a>
				@endif
				@if ($reason=='menu')
					<a class='btn btn-primary btn-xs' href='{{ URL::to('product_searches/menu/'. $class_code . '/' . $period_code . '/' . $week . '/'. $day . '/'. $product_search->product_code) }}'>+</a>
				@endif
			</td>
	</tr>
@endforeach
</tbody>
</table>
@endif
@if (isset($search)) 
	{{ $product_searches->appends(['class_code'=>$class_code, 'period_code'=>$period_code, 'week'=>$week, 'day'=>$day, 'set_code'=>$set_code, 'product_code'=>$product_code, 'search'=>$search,'reason'=>$reason,  'purchase_id'=>$purchase_id])->render() }}
	@else
	{{ $product_searches->appends(['class_code'=>$class_code, 'period_code'=>$period_code, 'week'=>$week, 'day'=>$day, 'set_code'=>$set_code, 'product_code'=>$product_code, 'reason'=>$reason, 'purchase_id'=>$purchase_id])->render() }}
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
	@if ($reason=='purchase_order')
	frameLine.src='/purchase_order_lines/index/{{ $purchase_id }}';
	@endif
	@if ($reason=='bom')
	frameLine.src='/bill_materials/index/{{ $product_code }}';
	@endif
	@if ($reason=='asset')
	frameLine.src='/order_sets/index/{{ $set_code }}';
	@endif
	@if ($reason=='menu')
	frameLine.src='/diet_menus/menu/{{ $class_code }}/{{ $period_code }}/{{ $week }}/{{ $day }}';
	@endif
</script>
@endif
@endsection
