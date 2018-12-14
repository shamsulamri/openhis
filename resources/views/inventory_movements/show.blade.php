@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>{{ $movement->movement->move_name }}</h1>
<h3>{{ $movement->store_code }}</h3>

<a class="btn btn-default" href="/inventory_movements" role="button">Back</a>
<a class="btn btn-default" href="#" role="button">Print</a>
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
	<div class="col-xs-5">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='950px' src='/purchase_lines/master_item/{{ $movement->move_id }}?reason=stock'></iframe>
	</div>
	@endif
	@if ($movement->move_posted == 0)
	<div class="col-xs-7">
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
</script>
@endsection
