@extends('layouts.app')

@section('content')
<style>
iframe { border: 1px #e5e5e5 solid; }
</style>
				
@if ($stock_input->move_code != 'receive')
<h1>Stock Movement</h1>
@else
<h1>Goods Receive</h1>
@endif

<!--
@if ($errors->has('stock')) <p class="help-block">{{ $errors->first('stock') }}</p> @endif
<h2>
{{ $stock_input->store->store_name }} <i class='fa fa-arrow-right'></i> {{ $stock_input->movement->move_name }}
@if ($stock_input->move_code == 'transfer')
	<i class='fa fa-arrow-right'></i> {{ $stock_input->store_transfer->store_name }}
@endif
</h2>
-->
<br>
{{ Form::open(['id'=>'myform','url'=>'/stock_input/post/'.$stock_input->input_id, 'class'=>'form-horizontal']) }}
@if ($stock_input->move_code != 'receive')
    <div class='form-group'>
        <label class='col-sm-2 control-label'>Movement Type</label>
        <div class='col-sm-10'>
            {{ Form::label('store', $stock_input->movement->move_name, ['class'=>'form-control']) }}
        </div>
    </div>
    <div class='form-group'>
        <label class='col-sm-2 control-label'>Source Store</label>
        <div class='col-sm-10'>
            {{ Form::label('store', $stock_input->store->store_name, ['class'=>'form-control']) }}
        </div>
    </div>
	@if ($stock_input->move_code == 'transfer')
    <div class='form-group'>
        <label class='col-sm-2 control-label'>Target Store</label>
        <div class='col-sm-10'>
            {{ Form::label('store', $stock_input->store_transfer->store_name, ['class'=>'form-control']) }}
        </div>
    </div>
	@endif
    <div class='form-group  @if ($errors->has('input_description')) has-error @endif'>
        <label class='col-sm-2 control-label'>Descriptions</label>
        <div class='col-sm-10'>
		@if ($stock_input->input_close==0)
            {{ Form::text('input_description', $stock_input->input_description, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
		@else
            {{ Form::label('input_description', $stock_input->input_description, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
		@endif
        </div>
    </div>
@else
    <div class='form-group'>
        {{ Form::label('purchase_id', 'Purchase Id',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::label('purchase_id', $purchase_order->purchase_id, ['class'=>'form-control']) }}
        </div>
    </div>
    <div class='form-group'>
        {{ Form::label('supplier_name', 'Supplier',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::label('supplier_name', $purchase_order->supplier->supplier_name, ['class'=>'form-control']) }}
        </div>
    </div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('invoice_number')) has-error @endif'>
						<label for='invoice_number' class='col-sm-4 control-label'>Invoice Number<span style='color:red;'> *</span></label>
						<div class='col-sm-8'>
							@if ($stock_input->input_close==0)
							{{ Form::text('invoice_number', $stock_receive->invoice_number, ['class'=>'form-control']) }}
							@else
							{{ Form::label('invoice_number', $stock_receive->invoice_number, ['class'=>'form-control']) }}
							@endif
							@if ($errors->has('invoice_number')) <p class="help-block">{{ $errors->first('invoice_number') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('invoice_date')) has-error @endif'>
						<label for='invoice_date' class='col-sm-4 control-label'>Invoice Date<span style='color:red;'> *</span></label>
						<div class='col-sm-8'>
							@if ($stock_input->input_close==0)
									<div class="input-group date">
										<input data-mask="99/99/9999" name="invoice_date" id="invoice_date" type="text" class="form-control" value='{{ $stock_receive->invoice_date }}'>
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
									@if ($errors->has('invoice_date')) <p class="help-block">{{ $errors->first('invoice_date') }}</p> @endif
							@else
									{{ Form::label('invoice_date', $stock_receive->invoice_date, ['class'=>'form-control']) }}
							@endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
						<label for='store_code' class='col-sm-4 control-label'>Receiving Store<span style='color:red;'> *</span></label>
						<div class='col-sm-8'>
						@if ($stock_input->input_close==0)
							{{ Form::select('store_code', $store, $stock_receive->store_code, ['class'=>'form-control','maxlength'=>'20']) }}
							@if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
						@else
							{{ Form::label('store', $stock_receive->store->store_name, ['class'=>'form-control']) }}
						@endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('delivery_number')) has-error @endif'>
						<label for='delivery_number' class='col-sm-4 control-label'>Delivery Number</label>
						<div class='col-sm-8'>
						@if ($stock_input->input_close==0)
							{{ Form::text('delivery_number', $stock_receive->delivery_number, ['class'=>'form-control']) }}
							@if ($errors->has('delivery_number')) <p class="help-block">{{ $errors->first('delivery_number') }}</p> @endif
						@else
							{{ Form::label('delivery_number', $stock_receive->delivery_number, ['class'=>'form-control']) }}
						@endif
						</div>
					</div>
			</div>
	</div>
    <div class='form-group  @if ($errors->has('delivery_number')) has-error @endif'>
        <label for='status' class='col-sm-2 control-label'></label>
        <div class='col-sm-10'>
				{{ Form::checkbox('close_transaction','1', 0) }} Transaction complete
        </div>
    </div>
<script>
		$('#invoice_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
</script>
@endif
@if ($stock_input->input_close==0)
{{ Form::submit('Post Movement', ['class'=>'btn btn-warning']) }}
<br>
<br>
@endif
{{ Form::close() }}

@if ($stock_input->input_close==0)
<div class="row">
	<div class="col-xs-5">
		<iframe name='frameIndex' id='frameIndex' width='100%' height='800px' src='/product_searches?reason=bulk&input_id={{ $stock_input->input_id }}'></iframe>
	</div>
	<div class="col-xs-7">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/stock_inputs/input/{{ $stock_input->input_id }}'><iframe>
	</div>
</div>
@else
<div class="row">
	<div class="col-xs-12">
		<iframe name='frameLine' id='frameLine' width='100%' height='800px' src='/stock_inputs/input/{{ $stock_input->input_id }}'><iframe>
	</div>
</div>
@endif
@endsection
