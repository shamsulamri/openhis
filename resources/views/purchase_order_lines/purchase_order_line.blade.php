
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-3 control-label'>Product</label>
        <div class='col-sm-9'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_price')) has-error @endif'>
						{{ Form::label('line_price', 'Price',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::label('line_price', $purchase_order_line->line_price, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_price')) <p class="help-block">{{ $errors->first('line_price') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
	@if ($purchase_order_line->purchaseOrder->purchase_posted==0)

					<div class='form-group  @if ($errors->has('line_quantity_ordered')) has-error @endif'>
						{{ Form::label('line_quantity_ordered', 'Quantity',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_quantity_ordered', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_quantity_ordered')) <p class="help-block">{{ $errors->first('line_quantity_ordered') }}</p> @endif
						</div>
					</div>
			</div>
	</div>

	@else
					<div class='form-group  @if ($errors->has('line_quantity_ordered')) has-error @endif'>
						{{ Form::label('line_quantity_ordered', 'Quantity',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
						@if ($purchase_order_line->line_quantity_ordered==0)
						<label for="line_price" class="form-control">
							{{ str_replace('.00','',$purchase_order_line->line_quantity_ordered) }}
						</label>
						@else
							{{ Form::label('line_quantity_ordered', str_replace('.00','',$purchase_order_line->line_quantity_ordered), ['class'=>'form-control']) }}
						@endif
							@if ($errors->has('line_quantity_ordered')) <p class="help-block">{{ $errors->first('line_quantity_ordered') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class='page-header'>
		<h4>Receiving Details</h4>
	</div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_batch_number')) has-error @endif'>
						{{ Form::label('line_batch_number', 'Batch Number',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_batch_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
							@if ($errors->has('line_batch_number')) <p class="help-block">{{ $errors->first('line_batch_number') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_expiry_date')) has-error @endif'>
						<label for='line_expiry_date' class='col-sm-3 control-label'>Expiry Date</label>
						<div class='col-sm-9'>
							<input id="line_expiry_date" name="line_expiry_date" type="text">
							@if ($errors->has('line_expiry_date')) <p class="help-block">{{ $errors->first('line_expiry_date') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_quantity_received')) has-error @endif'>
						{{ Form::label('line_quantity_received', 'Quantity Received #1',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_quantity_received', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_quantity_received')) <p class="help-block">{{ $errors->first('line_quantity_received') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('line_quantity_received_2')) has-error @endif'>
						{{ Form::label('line_quantity_received_2', 'Quantity Received #2',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('line_quantity_received_2', null, ['class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('line_quantity_received_2')) <p class="help-block">{{ $errors->first('line_quantity_received_2') }}</p> @endif
						</div>
					</div>
			</div>
	</div>


	@endif
	<br>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/purchase_order_lines/index/{{ $purchase_order_line->purchase_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('purchase_id',null) }}
	<script>
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
	</script>
