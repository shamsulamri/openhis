@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
<h1>{{ $purchase->document->document_name }}</h1>

	<div class="row">
			<div class="col-xs-3">
					<div class='form-group'>
						<a class="btn btn-default" href="/purchases" role="button">Back</a>
						<a class="btn btn-default" href="{{ Config::get('host.report_server') }}?report={{ $purchase->document_code=='indent_request'?'indent':'purchase' }}&id={{ $purchase->purchase_id }}&store_code={{ $purchase->store_code }}" role="button" target="_blank">Print</a> 
					</div>
			</div>
			<div class="col-xs-9">
					<div class='form-group'>
		@if ($purchase->purchase_posted == 0)
		{{ Form::open(['url'=>'purchase_lines/post/'.$purchase_id, 'class'=>'pull-right']) }}
			{{ method_field('POST') }}
			<table>
				<tr>
			@if ($purchase->document_code=='goods_receive')
					<td>
Receiving Store&nbsp;
					</td>
					<td>
            {{ Form::select('store_code', $store,$purchase->store_code, ['id'=>'store_code','class'=>'form-control']) }}
					</td>
					<td width=50>
					</td>
			@endif
					<td>
			{{ Form::checkbox('close_check', 1, false,['id'=> 'close_check', 'class'=>'i-checks']) }} Post transaction. &nbsp;&nbsp;
					</td>
					<td>
			{{ Form::submit('Submit', ['id'=>'close_button','class'=>'btn btn-primary']) }}
					</td>
				</tr>
			</table>
		{{ Form::close() }}
		@endif

@if (Gate::check('purchase_request') || Gate::check('indent_request'))
		@if ($purchase->purchase_posted==1 && ($purchase->document_code == 'purchase_request' || $purchase->document_code == 'indent_request')
			&& $purchase->status_code == null)
				{{ Form::open(['url'=>'purchase_lines/close/'.$purchase_id, 'class'=>'form-inline pull-right']) }}
					{{ method_field('POST') }}
            		{{ Form::select('status_code', $purchase_request_status,null, ['id'=>'status_code', 'class'=>'form-control']) }}
					{{ Form::submit('Submit', ['id'=>'close_button','class'=>'btn btn-primary']) }}
				{{ Form::close() }}
		@else
			<h3 class='pull-right'>
			@if ($purchase->purchaseRequestStatus)
			{{ $purchase->purchaseRequestStatus->status_name }}
			@endif
			</h3>
		@endif
@endif
					</div>
			</div>
	</div>


@if ($purchase->purchase_posted == 1)
	<h3 class='pull-right'>Posted document.</h3>
	<br>
	<br>
@endif
<div class="row">
	@if ($purchase->purchase_posted == 0)
	<div class="col-xs-5">
		<iframe name='frameProduct' id='frameProduct' width='100%' height='950px' src='/purchases/master_document?reason=purchase&purchase_id={{ $purchase_id }}' ></iframe>
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
	$("#status_code").change(function() {
		status = $("#status_code").val();
		if (status != '') {
				document.getElementById('close_button').disabled = false;
		} else {
				document.getElementById('close_button').disabled = true;
		}
	});

	function toggleCloseButton() {
		flag = $('#close_check').is(':checked');
		document.getElementById('close_button').disabled = !flag;
	}

	document.getElementById('close_button').disabled = true;
</script>
@endsection
