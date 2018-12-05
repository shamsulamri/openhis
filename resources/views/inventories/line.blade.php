@extends('layouts.app2')

@section('content')
@if ($inventories->total()>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Product</th>
    <th>UOM</th>
    <th>Book Quantity</th> 
    <th>Physical Quantity</th> 
    <th>Batch Number</th> 
	<th></th>
	</tr>
  </thead>
	<tbody>
<form method='POST' action='/inventories/save/{{ $move_id }}'>
		{{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
<a href='/inventories/confirm/{{ $move_id }}' class='btn btn-warning pull-right'>Post</a>
<br>
<br>
@foreach ($inventories as $inventory)
	<tr>
			<td>
					{{ $inventory->product->product_name }}
					<br>
					{{ $inventory->product_code }}
			</td>
			<td width='150'>
					{{ Form::select('unit_'.$inventory->inv_id, $helper->getUOM($inventory->product_code),$inventory->unit_code, ['id'=>'gender_code','class'=>'form-control','maxlength'=>'1']) }}
			</td>
			<td width='80'>
            		{{ Form::text('book_'.$inventory->inv_id, $inventory->inv_book_quantity, ['class'=>'form-control']) }}
			</td>
			<td width='80'>
            		{{ Form::text('physical_'.$inventory->inv_id, $inventory->inv_physical_quantity ?:0, ['class'=>'form-control']) }}
			</td>
			<td width='100'>
            		{{ Form::text('batch_'.$inventory->inv_id, $inventory->inv_batch_number, ['class'=>'form-control']) }}
			</td>
			<td align='right'>
					<a class='btn btn-danger btn-xs' href='{{ URL::to('inventories/delete/'. $inventory->inv_id) }}'>x</a>
			</td>
	</tr>
@endforeach
	<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
</form>
@endif
</tbody>
</table>
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
@endsection
