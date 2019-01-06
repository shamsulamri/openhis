@extends('layouts.app2')

@section('content')

<a class='btn btn-default' href='/inventory_movements/master_item/{{ $movement->move_id }}?reason=stock'>Items</a>
<a class='btn btn-default' href='/inventory_movements/master_document/{{ $movement->move_id }}?reason=stock'>Documents</a>
<a class='btn btn-default' href='/product_searches?reason=stock&move_id={{ $movement->move_id }}'>Products</a>
<br>
<br>
<form action='/inventory_movement/search_document' method='post'>
	<div class='input-group'>
		<input type='text' class='form-control' placeholder="Find" name='search' value='{{ isset($search) ? $search : '' }}' autocomplete='off' autofocus>
		<span class='input-group-btn'>
			<button type="submit" class="btn btn-md btn-primary"> <span class='glyphicon glyphicon-search'></span></button> 
		</span>
	</div>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
			{{ Form::hidden('move_id', $movement->move_id) }}
			{{ Form::hidden('reason', $reason) }}
</form>
@if ($documents->total()>0)
<br>
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Document</th> 
    <th>Movement</th>
    <th></th> 
	</tr>
  </thead>
	<tbody>
@foreach ($documents as $document)
	<tr>
			<td width='40%'>
					{{ $document->move_number }}
			</td>
			<td>
					<a href='{{ URL::to('inventory_movements/master_item/'. $movement->move_id . '/'.$document->move_id .'?reason=stock') }}'>
						{{$document->movement->move_name}}
					</a>
			</td>
			<!--
			<td>
					{{$document->store? $document->store->store_name: '-'}}
			</td>
			-->
			<td align='right' width='10'>
					<a class='btn btn-primary btn-xs' href='{{ URL::to('inventory_movement/convert/'. $document->move_id.'/'.$movement->move_id.'?reason='.$reason) }}'>
								<span class='glyphicon glyphicon-plus'></span>
					</a>
			</td>
	</tr>
@endforeach
@endif
</tbody>
</table>
@if (isset($search)) 
	{{ $documents->appends(['search'=>$search])->render() }}
	@else
	{{ $documents->render() }}
@endif
<br>
@if ($documents->total()>0)
	{{ $documents->total() }} records found.
@else
	No record found.
@endif

<script>
	var frameLine = parent.document.getElementById('frameLine');

	@if($reload=='true')
			@if($reason=='stock')
					frameLine.src='/inventories/detail/{{ $movement->move_id }}';
			@else
					frameLine.src='/purchase_lines/detail/{{ $purchase->purchase_id }}';
			@endif
	@endif
</script>
@endsection
