
	@if ($purchase_order->purchase_posted==0)
<h1>New Purchase Order</h1>
<br>
    <div class='form-group  @if ($errors->has('purchase_date')) has-error @endif'>
        <label for='purchase_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<input id="purchase_date" name="purchase_date" type="text">
            @if ($errors->has('purchase_date')) <p class="help-block">{{ $errors->first('purchase_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_code')) has-error @endif'>
        <label for='supplier_code' class='col-sm-3 control-label'>Supplier<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('supplier_code', $supplier,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('supplier_code')) <p class="help-block">{{ $errors->first('supplier_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_description')) has-error @endif'>
        {{ Form::label('purchase_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('purchase_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('purchase_description')) <p class="help-block">{{ $errors->first('purchase_description') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/purchase_orders" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	@endif

	@if ($purchase_order->purchase_posted==1)
	
	<h3>Stock Receive</h3>
	<br>

    <div class='form-group  @if ($errors->has('receive_datetime')) has-error @endif'>
        {{ Form::label('supplier_name', 'Supplier',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('supplier_name', $purchase_order->supplier->supplier_name, ['class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('receive_datetime')) has-error @endif'>
        {{ Form::label('purchase_id', 'Purchase Id',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::label('purchase_id', $purchase_order->purchase_id, ['class'=>'form-control']) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('store_code', 'Receiving Store',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($purchase_order->purchase_received==0)
					{{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
					@if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
			@else
            		{{ Form::label('store_code', $purchase_order->store->store_name, ['class'=>'form-control']) }}
			@endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('receive_datetime')) has-error @endif'>
        {{ Form::label('receive_datetime', 'Date/Time',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			@if ($purchase_order->purchase_received==0)
					<input id="receive_datetime" name="receive_datetime" type="text">
					@if ($errors->has('receive_datetime')) <p class="help-block">{{ $errors->first('receive_datetime') }}</p> @endif
			@else
					<label class='form-control'>
					{{ date('d F Y, H:i', strtotime($purchase_order->getReceiveDatetime())) }}
					</label>
			@endif
        </div>
    </div>


	<div class='page-header'>
		<h4>Invoice</h4>
	</div>
    <div class='form-group  @if ($errors->has('invoice_number')) has-error @endif'>
        {{ Form::label('invoice_number', 'Number',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('invoice_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('invoice_number')) <p class="help-block">{{ $errors->first('invoice_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('invoice_date')) has-error @endif'>
        {{ Form::label('invoice_date', 'Date',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
			<input id="invoice_date" name="invoice_date" type="text">
            @if ($errors->has('invoice_date')) <p class="help-block">{{ $errors->first('invoice_date') }}</p> @endif
        </div>
    </div>
	{{ Form::hidden('purchase_date', $purchase_order->purchase_date) }}
	{{ Form::hidden('supplier_code', $purchase_order->supplier_code) }}
	{{ Form::hidden('purchase_received',$purchase_order->purchase_received) }}

	@if ($purchase_order->purchase_received==1)
			{{ Form::hidden('store_code',$purchase_order->store_code) }}
	@endif
	<br>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/purchase_order_lines/index/{{ $purchase_order->purchase_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	@endif
	<script>
		$(function(){
				$('#purchase_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $purchase_order->purchase_date }}',
						maxYear: {{ $maxYear }},
						minYear: {{ $maxYear-5 }},
						customClass: 'select'
				});    
		});

		$(function(){
				$('#invoice_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $purchase_order->invoice_date }}',
						maxYear: 2016,
						minYear: 1900,
						customClass: 'select'
				});    
		});
		$(function(){
				$('#receive_datetime').combodate({
						format: "DD/MM/YYYY HH:mm",
						template: "DD MMMM YYYY     HH : mm",
						value: '{{ $purchase_order->receive_datetime }}',
						maxYear: {{ $maxYear }},
						minYear: 1900,
						customClass: 'select'
				});    
		});
	</script>
