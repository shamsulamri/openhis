@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>{{ $purchase->document->document_name }}</h1>

<a class="btn btn-default" href="/purchases" role="button">Back</a>
<a class="btn btn-default" href="{{ Config::get('host.report_server') }}/ReportServlet?report=purchase&id={{ $purchase->purchase_id }}" role="button" target="_blank">Print</a> 
@if ($purchase->purchase_posted == 0)
{{ Form::open(['url'=>'purchase_lines/close/'.$purchase_id, 'class'=>'pull-right']) }}
	{{ method_field('POST') }}
	{{ Form::checkbox('close_check', 1, false,['id'=> 'close_check', 'class'=>'i-checks']) }} Post transaction. &nbsp;&nbsp;
	{{ Form::submit('Submit', ['id'=>'close_button','class'=>'btn btn-primary']) }}
{{ Form::close() }}
@else	
	<h3 class='pull-right'>Posted document.</h3>
@endif

<br>
<br>
<div class="row">
	@if ($purchase->purchase_posted == 0)
	<div class="col-xs-5">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='950px' src='/purchase_lines/master_item/{{ $purchase_id }}?reason=purchase'></iframe>
	</div>
	@endif
	@if ($purchase->purchase_posted == 0)
	<div class="col-xs-7">
	@else
	<div class="col-xs-12">
	@endif
		<iframe name='frameLine' id='frameLine' width='100%' height='950px' src='/purchase_lines/detail/{{ $purchase_id }}' ></iframe>
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
