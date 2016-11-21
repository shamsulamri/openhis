@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
@include('products.id')

<h1>Bill of Materials</h1>
<br>
<div class="row">
	<div class="col-xs-6">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='800px' src='/product_searches?reason=bom&product_code={{ $product_code }}'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/bill_materials/index/{{ $product_code }}'><iframe>
	</div>
</div>
@endsection
