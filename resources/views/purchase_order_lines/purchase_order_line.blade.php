
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
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
						{{ Form::label('unit_code', 'Unit of Measure',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::select('uom', $uom_list, null, ['id'=>'uom_list', 'onchange'=>'uomChanged()']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group'>  
						{{ Form::label('label', 'UOM Rate',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
						{{ Form::label('label_rate', '1',['id'=>'label_rate','class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_unit_price')) has-error @endif'>
						{{ Form::label('line_unit_price', 'Price',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_unit_price', $purchase_order_line->line_unit_price, ['id'=>'line_unit_price', 'class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_unit_price')) <p class="help-block">{{ $errors->first('line_unit_price') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_quantity')) has-error @endif'>
						{{ Form::label('line_quantity', 'Order Quantity',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_quantity')) <p class="help-block">{{ $errors->first('line_quantity') }}</p> @endif
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
					<div class='form-group  @if ($errors->has('tax_rate')) has-error @endif'>
						{{ Form::label('tax_rate', 'Tax Rate',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('tax_rate', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('tax_rate')) <p class="help-block">{{ $errors->first('tax_rate') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<br>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/purchase_order_lines/index/{{ $purchase_order_line->purchase_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('purchase_id',null) }}
	{{ Form::hidden('unit_code','unit', ['id'=>'unit_code']) }}
	{{ Form::hidden('uom_rate','1', ['id'=>'uom_rate']) }}
	<script>



		/*
		$(function(){
				$('#line_expiry_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $purchase_order_line->line_expiry_date }}',
						maxYear: 2020,
						minYear: 2016,
						customClass: 'select'
				});    
		});
		*/

		$('#line_expiry_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('#line_receive_date_1').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
		$('#line_receive_date_2').datepicker({
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

				@foreach ($product_uoms as $uom)

				if (uom_list.value == '{{ $uom->unit_code }}') {
						price.value = {{ $uom->uom_cost }};
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
