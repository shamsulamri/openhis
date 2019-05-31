@extends('layouts.app2')

@section('content')
@if ($movement->tag_code == 'transfer_in' && $movement->move_code == 'stock_receive')
<a class='btn btn-default btn-sm' href='/inventory_movements/master_document/{{ $movement->move_id }}?reason=stock'>Documents</a>
@else
<a class='btn btn-default btn-sm' href='/inventory_movements/master_item/{{ $movement->move_id }}?reason=stock'>Items</a>
<a class='btn btn-default btn-sm' href='/inventory_movements/master_document/{{ $movement->move_id }}?reason=stock'>Documents</a>
<a class='btn btn-default btn-sm' href='/product_searches?reason=stock&move_id={{ $movement->move_id }}'>Products</a>
<a class='btn btn-default btn-sm' href='/purchases/master_document?reason=request&move_id={{ $movement->move_id }}'>Request</a>
@endif
<br>
<br>
<form action='/inventory_movement/search_item' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
			{{ Form::hidden('to', $movement->move_id) }}
			{{ Form::hidden('from', $document_id) }}
			{{ Form::hidden('reason', $reason) }}
</form>

			<br>
@if ($movement_from)
<h4>Document: {{ $movement_from->move_number }}</h4>
@endif
@if ($inventories->total()>0)
<form action='/inventory_movement/post_item' method='post'>
<table class="table table-hover">
 <thead>
	<tr> 
	<th>
			<a class="btn btn-default btn-xs" href="javascript:toggleCheck()" role="button">
				<i class="fa fa-check"></i>
			</a>
	</th>
    <th>Item</th> 
	<th>
		<div align='right'>
			Quantity
		<div>
	</th>
	</tr>
  </thead>
	<tbody>
@foreach ($inventories as $item)
	<tr>
			<td width='10'>
				{{ Form::checkbox($item->inv_id, 1, null,['id'=>$item->inv_id,'class'=>'i-checks']) }}
			</td>
			<td>
					<a href='{{ URL::to('inventories/'. $item->inv_id . '/edit') }}'>
						{{$item->product->product_name}}
					</a>
						<br>
						{{$item->product_code}}
			</td>
			<td width='10'>
					<div align='right'>
					{{ $item->inv_physical_quantity }}
					</div>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
			{{ Form::submit('Add', ['class'=>'btn btn-primary']) }}
			<input type='hidden' name="_token" value="{{ csrf_token() }}">
			<input type='hidden' name="to" value="{{ $movement->move_id }}">
			@if ($document_id)
			<input type='hidden' name="from" value="{{ $document_id }}">
			@endif
<br>
@if (isset($search)) 
	{{ $inventories->appends(['search'=>$search])->render() }}
	@else
	{{ $inventories->render() }}
@endif
<br>
@if ($inventories->total()>0)
	{{ $inventories->total() }} records found.
@else
	No record found.
@endif
<script>
	var frameLine = parent.document.getElementById('frameLine');

	@if($reload=='true')
			frameLine.src='/inventories/detail/{{ $movement->move_id }}';
	@endif

	function toggleCheck(flag) {
		@foreach ($inventories as $item)
			checked = $('#{{ $item->inv_id }}').is(':checked');
			$('#{{ $item->inv_id }}').prop('checked', !checked).iCheck('update');
		@endforeach
	}
</script>
@endsection
