@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>{{ $movement->move_code }}</h1>
<h3>{{ $movement->store_code }}</h3>

<a class="btn btn-default" href="/inventory_movements" role="button">Back</a>
<br>
<br>
<div class="row">
	<div class="col-xs-5">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='950px' src='/product_searches?reason={{ $movement->move_code }}&move_id={{ $movement->move_id }}'></iframe>
	</div>
	<div class="col-xs-7">
		<iframe name='frameLine' id='frameLine' width='100%' height='950px' src='/inventories/line/{{ $movement->move_id }}'></iframe>
	</div>
</div>
@endsection
