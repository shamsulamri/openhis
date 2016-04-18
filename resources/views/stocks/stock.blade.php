
    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>Store</label>
        <div class='col-sm-10'>
            {{ Form::label('store_code', $store->store_name, ['class'=>'form-control']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>Movement Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('move_code', $move,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_date')) has-error @endif'>
        <label for='stock_date' class='col-sm-2 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			<input id="stock_date" name="stock_date" type="text">
            @if ($errors->has('stock_date')) <p class="help-block">{{ $errors->first('stock_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_quantity')) has-error @endif'>
        <label for='stock_quantity' class='col-sm-2 control-label'>Quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('stock_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('stock_quantity')) <p class="help-block">{{ $errors->first('stock_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('stock_description')) has-error @endif'>
        {{ Form::label('stock_description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('stock_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('stock_description')) <p class="help-block">{{ $errors->first('stock_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stocks/{{ $product->product_code }}/{{ $store->store_code }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('product_code', null) }}
	{{ Form::hidden('store_code', $store->store_code) }}
	<script>
		$(function(){
				$('#stock_date').combodate({
						format: "DD/MM/YYYY HH:mm",
						template: "DD MMMM YYYY     HH : mm",
						value: '{{ $stock->stock_date }}',
						maxYear: {{ $maxYear }},
						minYear: 1900,
						customClass: 'select'
				});    
		});
	</script>
