
	@if ($purchase_order->purchase_posted==0)
<h1>New Purchase Order</h1>
<br>
    <div class='form-group  @if ($errors->has('purchase_date')) has-error @endif'>
        <label for='purchase_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<div class="input-group date">
				{{ Form::text('purchase_date',null, ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'purchase_date']) }}
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
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

	@if ($purchase_order->purchase_received != 1)
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('receive_datetime')) has-error @endif'>
						{{ Form::label('date', 'Date',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							<div class="input-group date">
								{{ Form::text('receive_date',$receive_datetime->format('d/m/Y'), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'receive_date']) }}
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('receive_datetime')) <p class="help-block">{{ $errors->first('receive_datetime') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-3">
					<div class='form-group  @if ($errors->has('receive_datetime')) has-error @endif'>
						{{ Form::label('Time', 'Time',['class'=>'col-md-2 control-label']) }}
						<div class='col-md-10'>
								<div name="receive_time" class="input-group clockpicker" data-autoclose="true">
										{{ Form::text('receive_time', $receive_datetime->format('H:i'), ['class'=>'form-control','data-mask'=>'99:99','id'=>'receive_time']) }}
										<span class="input-group-addon">
											<span class="fa fa-clock-o"></span>
										</span>
								</div>

						</div>
					</div>
			</div>
	</div>
	@else
    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('date_receive', 'Date Received',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
				{{ Form::label('date_receive', $purchase_order->receive_datetime, ['class'=>'form-control']) }}
        </div>
    </div>
	@endif

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
			<div class="input-group date">
				{{ Form::text('invoice_date',null, ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'invoice_date']) }}
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
            @if ($errors->has('invoice_date')) <p class="help-block">{{ $errors->first('invoice_date') }}</p> @endif
        </div>
    </div>
	{{ Form::hidden('purchase_date', $purchase_order->purchase_date) }}
	{{ Form::hidden('supplier_code', $purchase_order->supplier_code) }}
	{{ Form::hidden('purchase_received',$purchase_order->purchase_received) }}
	{{ Form::hidden('receive_datetime', null, ['id'=>'receive_datetime']) }}

	@if ($purchase_order->purchase_received==1)
			{{ Form::hidden('store_code',$purchase_order->store_code) }}
	@endif
	<br>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/purchase_order_lines/index/{{ $purchase_order->purchase_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary', 'onclick'=>'javascript:setReceiveDatetime()']) }}
        </div>
    </div>

	@endif
	<script>
		function setReceiveDatetime() {
				$datetime = document.getElementById('receive_date').value + " " + document.getElementById('receive_time').value; 
				document.getElementById('receive_datetime').value=$datetime;
		}
		/*
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
		*/

		$('#invoice_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		$('#receive_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		$('#purchase_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	</script>
