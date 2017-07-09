
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
					<div class='form-group  @if ($errors->has('line_price')) has-error @endif'>
						{{ Form::label('line_price', 'Price',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_price', $purchase_order_line->line_price, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_price')) <p class="help-block">{{ $errors->first('line_price') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_quantity_ordered')) has-error @endif'>
						{{ Form::label('line_quantity_ordered', 'Order Quantity',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_quantity_ordered', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_quantity_ordered')) <p class="help-block">{{ $errors->first('line_quantity_ordered') }}</p> @endif
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
	</script>
