@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
				
<h1>Bulk Stock Movement</h1>
<h2>
{{ $stock_input->store->store_name }} <i class='fa fa-arrow-right'></i> {{ $stock_input->movement->move_name }}
@if ($stock_input->move_code == 'transfer')
	<i class='fa fa-arrow-right'></i> {{ $stock_input->store_transfer->store_name }}
@endif
</h2>
<br>
<a class="btn btn-warning" href="/stock_input/post/{{ $stock_input->input_id }}" role="button">Post Movement</a>
<br>
<br>
<div class="row">
	<div class="col-xs-5">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='800px' src='/product_searches?reason=bulk&input_id={{ $stock_input->input_id }}'></iframe>
	</div>
	<div class="col-xs-7">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/stock_inputs/input/{{ $stock_input->input_id }}'><iframe>
	</div>
</div>
@endsection
