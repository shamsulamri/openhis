
@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
				
<h1><a href='{{ URL::to('sets') }}'>Order Set List</a> > {{ $set->set_name }}</h1>
<br>
<div class="row">
	<div class="col-xs-6">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='800px' src='/product_searches?reason=asset&set_code={{ $set_code }}'></iframe>
	</div>
	<div class="col-xs-6">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/order_sets/index/{{ $set_code }}'><iframe>
	</div>
</div>
@endsection
