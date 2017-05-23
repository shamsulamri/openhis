
    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-3 control-label'>Store</label>
        <div class='col-sm-9'>
            {{ Form::label('store_code', $store->store_name, ['class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-3 control-label'>On Hand</label>
        <div class='col-sm-9'>
<?php
$on_hand = $stock_helper->getStockCountByStore($product->product_code, $store_code);
?>
			<label class='form-control'>{{ $on_hand }}</label>
        </div>
    </div>
    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-3 control-label'>Movement Type<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('move_code', $move,null, ['id'=>'move_code', 'onchange'=>'checkMovementType()' ,'class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_quantity')) has-error @endif'>
        <label for='stock_quantity' class='col-sm-3 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('stock_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('stock_quantity')) <p class="help-block">{{ $errors->first('stock_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code_transfer')) has-error @endif'>
        <label for='store_code_transfer' class='col-sm-3 control-label'>Transfer Store</label>
        <div class='col-sm-9'>
            {{ Form::select('store_code_transfer', $stores,null, ['id'=>'store_code_transfer', 'class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code_transfer')) <p class="help-block">{{ $errors->first('store_code_transfer') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_description')) has-error @endif'>
        {{ Form::label('stock_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('stock_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('stock_description')) <p class="help-block">{{ $errors->first('stock_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('loan_id')) has-error @endif'>
        {{ Form::label('loan_id', 'Loan Id',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('loan_id', null, ['class'=>'form-control','placeholder'=>'Reference to loan ID if available','rows'=>'4']) }}
            @if ($errors->has('loan_id')) <p class="help-block">{{ $errors->first('loan_id') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('stock_datetime')) has-error @endif'>
						{{ Form::label('date', 'Date',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							<div class="input-group date">
								{{ Form::text('stock_date',date('d/m/Y', strtotime($stock->stock_datetime)), ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'stock_date']) }}
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('stock_datetime')) <p class="help-block">{{ $errors->first('stock_datetime') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-3">
					<div class='form-group  @if ($errors->has('stock_datetime')) has-error @endif'>
						{{ Form::label('Time', 'Time',['class'=>'col-md-2 control-label']) }}
						<div class='col-md-10'>
								<div name="stock_time" class="input-group clockpicker" data-autoclose="true">
										{{ Form::text('stock_time', date('H:i', strtotime($stock->stock_datetime)), ['class'=>'form-control','data-mask'=>'99:99','id'=>'stock_time']) }}
										<span class="input-group-addon">
											<span class="fa fa-clock-o"></span>
										</span>
								</div>

						</div>
					</div>
			</div>
	</div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/stocks/{{ $product->product_code }}/{{ $store->store_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary', 'onclick'=>'javascript:setDateTime()']) }}
        </div>
    </div>

	{{ Form::hidden('product_code', null) }}
	{{ Form::hidden('store_code', $store->store_code) }}
	{{ Form::hidden('stock_datetime', null, ['id'=>'stock_datetime']) }}
	
	<script>

		document.getElementById('store_code_transfer').disabled = true;
		checkMovementType();

		function setDateTime() {
				$datetime = document.getElementById('stock_date').value + " " + document.getElementById('stock_time').value; 
				document.getElementById('stock_datetime').value=$datetime;
		}

		function checkMovementType() {
			moveType = document.getElementById('move_code');
			store = document.getElementById('store_code_transfer');
			if (moveType.value=='transfer') {
				store.disabled = false;
			} else {
				store.value = "";
				store.disabled = true;
			}
		}

		$('#stock_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('.clockpicker').clockpicker();

         $(document).ready(function(){
             $("#form").validate({
                 rules: {
                    stock_quantity: {
							 number: true,
					},
				 }
             });
        });

    </script>
