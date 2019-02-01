
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-3 control-label'>Code</label>
        <div class='col-sm-9'>
            {{ Form::label('product_code', $product->product_code, ['class'=>'form-control']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('product_name')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>Product</label>
        <div class='col-sm-9'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control']) }}
            @if ($errors->has('product_name')) <p class="help-block">{{ $errors->first('product_name') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-4">
					<div class='form-group  @if ($errors->has('line_quantity')) has-error @endif'>
						{{ Form::label('line_quantity', 'Quantity',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_quantity', null, ['id'=>'line_quantity', 'class'=>'form-control','onchange'=>'uomChanged()']) }}
							@if ($errors->has('line_quantity')) <p class="help-block">{{ $errors->first('line_quantity') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-2">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						{{ Form::label('unit_code', 'UOM',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::select('uom', $uom_list, $purchase_line->unit_code, ['id'=>'uom_list', 'onchange'=>'uomChanged()']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_unit_price')) has-error @endif'>
						{{ Form::label('line_unit_price', 'Unit Price',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_unit_price', $purchase_line->line_unit_price, ['id'=>'line_unit_price', 'class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_unit_price')) <p class="help-block">{{ $errors->first('line_unit_price') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('tax_code')) has-error @endif'>
						{{ Form::label('tax_code', 'Tax',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::select('tax_code', $tax_code, $product->tax_code, ['id'=>'tax_code', 'onchange'=>'taxChanged()']) }}
							@if ($errors->has('tax_code')) <p class="help-block">{{ $errors->first('tax_code') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<!--
					<div class='form-group  @if ($errors->has('tax_rate')) has-error @endif'>
						{{ Form::label('tax_rate', 'Tax Rate',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('tax_rate', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tax_rate')) <p class="help-block">{{ $errors->first('tax_rate') }}</p> @endif
						</div>
					</div>
					-->
			</div>
	</div>
	@if ($purchase->document_code == 'goods_receive')
	<hr>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('batch_number')) has-error @endif'>
						{{ Form::label('batch_number', 'Batch',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('batch_number', $purchase_line->batch_number, ['id'=>'batch_number', 'class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('batch_number')) <p class="help-block">{{ $errors->first('batch_number') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('expiry_date')) has-error @endif'>
						{{ Form::label('expiry_date', 'Expiry Date',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							<div class="input-group date">
								{{ Form::text('expiry_date',null, ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'expiry_date']) }}
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('expiry_date')) <p class="help-block">{{ $errors->first('expiry_date') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	@endif
	<br>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/purchase_lines/detail/{{ $purchase_line->purchase_id }}" role="button">Cancel</a>
			@if ($purchase_line->line_posted == 0)
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
			@endif
        </div>
    </div>

	{{ Form::hidden('purchase_id',null) }}
	{{ Form::hidden('unit_code','unit', ['id'=>'unit_code']) }}
	{{ Form::hidden('uom_rate','1', ['id'=>'uom_rate']) }}
	<script>
		$('#expiry_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		function uomChanged() {
				var price = document.getElementById('line_unit_price');
				var uom_list = document.getElementById('uom_list');
				var unit_code = document.getElementById('unit_code');
				var uom_rate = document.getElementById('uom_rate');
				var label_rate = document.getElementById('label_rate');
				var line_quantity = document.getElementById('line_quantity');

				@foreach ($product_uoms as $uom)

				if (uom_list.value == '{{ $uom->unit_code }}') {
						price.value = {{ $uom->uom_cost?:0 }};
						unit_code.value = '{{ $uom->unit_code }}';
						uom_rate.value = '{{ $uom->uom_rate }}';
						label_rate.innerHTML = '{{ $uom->uom_rate }}';
				}
				@endforeach
		}

		function taxChanged() {

		}

		uomChanged();
	</script>
