    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_name' class='col-sm-3 control-label'>Item</label>
        <div class='col-sm-9'>
            {{ Form::label('product_name', $product->product_name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='user' class='col-sm-3 control-label'>Ordered By</label>
        <div class='col-sm-9'>
            {{ Form::label('user', $order_task->user->name, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	@if ($order_task->orderInvestigation)
    <div class='form-group  @if ($errors->has('product_code')) has-error @endif'>
        <label for='user' class='col-sm-3 control-label'>Investigation Date</label>
        <div class='col-sm-9'>
            {{ Form::label('execute', $order_task->orderInvestigation->investigation_date, ['class'=>'form-control','placeholder'=>'',]) }}
        </div>
    </div>
	@endif
    <div class='form-group  @if ($errors->has('order_custom_id')) has-error @endif'>
        {{ Form::label('order_custom_id', 'Custom Id',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('order_custom_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_custom_id')) <p class="help-block">{{ $errors->first('order_custom_id') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('order_quantity_supply')) has-error @endif'>
        {{ Form::label('order_quantity_supply', 'Quantity Supply',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('order_quantity_supply', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_quantity_supply')) <p class="help-block">{{ $errors->first('order_quantity_supply') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_report')) has-error @endif'>
        {{ Form::label('order_report', 'Report',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('order_report', null, ['id'=>'order_report','onkeyup'=>'taskCompleted()','class'=>'form-control','placeholder'=>'','rows'=>'8']) }}
        </div>
    </div>

	@if ($product->product_stocked)
    <div class='form-group  @if ($errors->has('stock_code')) has-error @endif'>
        {{ Form::label('stock_code', 'Store',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
    		{{ Form::select('store_code', $store, null, ['class'=>'form-control']) }}
        </div>
    </div>
	@endif
	<!--
    <div class='form-group  @if ($errors->has('order_discount')) has-error @endif'>
        {{ Form::label('order_discount', 'Discount',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('order_discount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_discount')) <p class="help-block">{{ $errors->first('order_discount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('order_completed')) has-error @endif'>
        {{ Form::label('order_completed', 'Completed',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('order_completed', '1',null,['id'=>'order_completed']) }}
            @if ($errors->has('order_completed')) <p class="help-block">{{ $errors->first('order_completed') }}</p> @endif
        </div>
    </div>
	-->

	
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
            <a class="btn btn-default" href="/order_tasks/task/{{ $encounter_id }}/{{ $order_task->product->location_code }}" role="button">Cancel</a>
        </div>
    </div>
            {{ Form::hidden('consultation_id', null) }}


<!--
<script type="text/javascript">
	function taskCompleted() {
		var report = document.getElementById('order_report').value;

		if (report.trim()) {
				document.getElementById('order_completed').checked = true;
		} else {
				document.getElementById('order_completed').checked = false;
		}

	}

</script>
-->


