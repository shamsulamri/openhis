@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #C0C0C0 solid; }
</style>
<h1>Purchase Orders / Line Items</h1>
<br>
@if ($purchase_order->purchase_received==0)
<div class="row">
	<div class="col-xs-5">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='800px' src='/product_searches?reason=purchase_order&purchase_id={{ $purchase_id }}'></iframe>
	</div>
	<div class="col-xs-7">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/purchase_order_lines/index/{{ $purchase_id }}'></iframe>
	</div>
</div>
@else
<div class="row">
	<div class="col-xs-12">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/purchase_order_lines/index/{{ $purchase_id }}'></iframe>
	</div>
</div>
@endif
@endsection
