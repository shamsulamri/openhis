@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>{{ $movement->movement->move_name }}</h1>
<h3>{{ ($movement->store)?$movement->store->store_name:'' }}</h3>
<h3>{{ $movement->move_number }}</h3>
<br>

<a class="btn btn-default" href="/inventory_movements" role="button">Back</a>
<a class="btn btn-default" href="{{ Config::get('host.report_server') }}/ReportServlet?report=stock_movement&id={{ $movement->move_id }}" role="button" target="_blank">Print</a> 
@if ($movement->move_posted == 0)
{{ Form::open(['url'=>'inventories/post/'.$movement->move_id, 'class'=>'pull-right']) }}
	{{ method_field('POST') }}
	{{ Form::checkbox('close_check', 1, false,['id'=> 'close_check', 'class'=>'i-checks']) }} Post transaction. &nbsp;&nbsp;
	{{ Form::submit('Submit', ['id'=>'close_button','class'=>'btn btn-primary']) }}
{{ Form::close() }}
@endif
<br>
<br>
<div class="row">
	@if ($movement->move_posted == 0)
	<div class="col-xs-4">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='950px' src='/inventory_movements/master_document/{{ $movement->move_id }}?reason=stock'></iframe>
	</div>
	@endif
	@if ($movement->move_posted == 0)
	<div class="col-xs-8">
	@else
	<div class="col-xs-12">
	@endif
		<iframe name='frameLine' id='frameLine' width='100%' height='950px' src='/inventories/detail/{{ $movement->move_id }}?reason={{ $reason }}'></iframe>
	</div>
</div>

<script>

	$("#close_check").on("ifChanged", toggleCloseButton);

	function toggleCloseButton() {
		flag = $('#close_check').is(':checked');
		document.getElementById('close_button').disabled = !flag;
	}

	document.getElementById('close_button').disabled = true;
	//document.getElementById('close_check').disabled = true;

	function resetPost() {
			//checked = $('#close_check').is(':checked');
			$('#close_check').prop('checked', false).iCheck('update');
			$('#close_button').prop('disabled', true);
	}

	function disableCheck(flag) {
			//checked = $('#close_check').is(':checked');
			//$('#close_check').prop('checked', !flag).iCheck('update');
			$('#close_check').prop('disabled', flag).iCheck('update');
			//$('#close_button').prop('disabled', flag);
	}
</script>
@endsection
