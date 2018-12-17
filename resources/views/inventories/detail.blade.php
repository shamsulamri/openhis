@extends('layouts.app2')

@section('content')
@if ($inventories->total()>0)
<table class="table table-condensed">
 <thead>
	<tr> 
@if ($movement->move_posted == 0)
	<th>
			<a class="btn btn-default btn-xs" href="javascript:toggleCheck()" role="button">
				<i class="fa fa-check"></i>
			</a>
	</th>
@endif
    <th>Product</th>
    <th><div align='center'>Book Quantity</div></th> 
    <th><div align='center'>Physical Quantity</div></th> 
    <th><div align='center'>UOM</div></th> 
    <th><div align='center'>Batch Number</div></th> 
    <th><div align='center'>Expiry Date</div></th> 
	</tr>
  </thead>
	<tbody>
<form method='POST' action='/inventories/submit/{{ $move_id }}' class='form-horizontal'>
@if ($movement->move_posted == 0)
		{{ Form::submit('Save', ['name'=>'button', 'class'=>'btn btn-primary']) }}
<br>
<br>
@endif
@foreach ($inventories as $inventory)
	<tr>
@if ($movement->move_posted == 0)
			<td>
					{{ Form::checkbox($inventory->inv_id, 1, null,['id'=> $inventory->inv_id, 'class'=>'i-checks', 'onclick'=>'checkItem('.$inventory->inv_id.')']) }}
			</td>
@endif
			<td>
					{{ $inventory->product->product_name }}
					<br>
					{{ $inventory->product_code }}
			</td>
			<td width='15%' align='center'>
				@if ($movement->move_posted==0) {{ Form::label('book_'.$inventory->inv_id, $inventory->inv_book_quantity ?:'-', ['class'=>'form-control']) }} @else {{ $inventory->inv_book_quantity }} @endif
			</td>
			<td width='15%' align='center'>
				@if ($movement->move_posted==0) 
            		{{ Form::text('physical_'.$inventory->inv_id, $inventory->inv_physical_quantity ?:0, ['class'=>'form-control']) }}
				@else
					{{ $inventory->inv_physical_quantity }}
				@endif
			</td>
			<td width='15%' align='center'>
				@if ($movement->move_posted==0) 
					{{ Form::select('unit_'.$inventory->inv_id, $helper->getUOM($inventory->product_code),$inventory->unit_code, ['id'=>'gender_code','class'=>'form-control','maxlength'=>'1']) }}
				@else
					{{ $inventory->unit->unit_name }}
				@endif
			</td>
			<td align='center'>
				@if ($movement->move_posted==0) 
            		{{ Form::text('batch_'.$inventory->inv_id, $inventory->inv_batch_number, ['class'=>'form-control']) }}
				@else
					{{ $inventory->inv_batch_number }}
				@endif
			</td>
			<td align='center'>
				<div class="input-group date">
				@if ($movement->move_posted==0) 
						<input data-mask="99/99/9999" name="inv_expiry_date_{{ $inventory->inv_id }}" id="inv_expiry_date_{{ $inventory->inv_id }}" type="text" class="form-control" value='{{ $inventory->batch()?$inventory->batch()->batch_expiry_date:'' }}'>
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				@else
					{{ $inventory->batch()?$inventory->batch()->batch_expiry_date:'' }}
				@endif
				</div>
			</td>
			<!--
			<td align='right'>
				@if ($movement->move_posted == 0)
					<a class='btn btn-danger' href='{{ URL::to('inventories/delete/'. $inventory->inv_id) }}'>-</a>
				@endif
			</td>
			-->
	</tr>
	{{ Form::hidden('chk_'.$inventory->inv_id, 0, ['id'=>'chk_'.$inventory->inv_id]) }}
@endforeach
	<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
<table>
@if ($movement->move_posted == 0)
	{{ Form::submit('Delete', ['name'=>'button', 'class'=>'btn btn-danger']) }}
@endif
</table>
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
<script>
	function toggleCheck() {
		@foreach ($inventories as $inventory)
			checked = $('#{{ $inventory->inv_id }}').is(':checked');
			$('#{{ $inventory->inv_id }}').prop('checked', !checked).iCheck('update');
			$('#chk_{{ $inventory->inv_id }}').val(!checked?1:0);
		@endforeach

	}

	function checkItem(id) {
			alert(id)
	}

	@foreach ($inventories as $inventory)
		$('#{{ $inventory->inv_id }}').on('ifChecked', function() {
			document.getElementById('chk_{{ $inventory->inv_id }}').value = 1;
		})
		$('#{{ $inventory->inv_id }}').on('ifUnchecked', function() {
			document.getElementById('chk_{{ $inventory->inv_id }}').value = 0;
		})
	@endforeach

	@foreach ($inventories as $inventory)
		$('#inv_expiry_date_{{ $inventory->inv_id }}').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	@endforeach
</script>
@endsection
