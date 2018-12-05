@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>{{ $purchase->document->document_name }}</h1>
<a class="btn btn-default" href="/purchases" role="button">Close</a>
<br>
<br>
<div class="row">
	<div class="col-xs-5">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='950px' src='/purchase_lines/master_item/{{ $purchase_id }}'></iframe>
	</div>
	<div class="col-xs-7">
		<iframe name='frameLine' id='frameLine' width='100%' height='950px' ></iframe>
	</div>
</div>
@endsection
