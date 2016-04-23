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

</form>
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
					<a href='{{ URL::to('product_searches/'. $product_search->product_code . '/edit') }}'>
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
			</td>
	</tr>
@endforeach
</tbody>
</table>
@endif
@if (isset($search)) 
	{{ $product_searches->appends(['product_code'=>$product_code, 'search'=>$search,'reason'=>$reason,  'purchase_id'=>$purchase_id])->render() }}
	@else
	{{ $product_searches->appends(['product_code'=>$product_code, 'reason'=>$reason, 'purchase_id'=>$purchase_id])->render() }}
@endif
<br>
@if ($product_searches->total()>0)
	{{ $product_searches->total() }} records found.
@else
	No record found.
@endif

<script>
	var frameLine = parent.document.getElementById('frameLine');
	frameLine.contentWindow.location.reload();
</script>
@endsection
