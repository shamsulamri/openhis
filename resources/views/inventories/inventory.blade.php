
    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        {{ Form::label('store_code', 'store_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code_transfer')) has-error @endif'>
        {{ Form::label('store_code_transfer', 'store_code_transfer',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('store_code_transfer', $store_transfer,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code_transfer')) <p class="help-block">{{ $errors->first('store_code_transfer') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('inv_datetime')) has-error @endif'>
        <label for='inv_datetime' class='col-sm-2 control-label'>inv_datetime<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('inv_datetime', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('inv_datetime')) <p class="help-block">{{ $errors->first('inv_datetime') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('inv_quantity')) has-error @endif'>
        <label for='inv_quantity' class='col-sm-2 control-label'>inv_quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('inv_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('inv_quantity')) <p class="help-block">{{ $errors->first('inv_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('inv_value')) has-error @endif'>
        <label for='inv_value' class='col-sm-2 control-label'>inv_value<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('inv_value', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('inv_value')) <p class="help-block">{{ $errors->first('inv_value') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('inv_description')) has-error @endif'>
        {{ Form::label('inv_description', 'inv_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('inv_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('inv_description')) <p class="help-block">{{ $errors->first('inv_description') }}</p> @endif
        </div>
    </div>

	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('inv_batch_number')) has-error @endif'>
						{{ Form::label('inv_batch_number', 'Batch Number',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							{{ Form::text('inv_batch_number', null, ['id'=>'inv_batch_number', 'class'=>'form-control','placeholder'=>'',]) }}
							@if ($errors->has('inv_batch_number')) <p class="help-block">{{ $errors->first('inv_batch_number') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('inv_expiry_date')) has-error @endif'>
						{{ Form::label('inv_expiry_date', 'Expiry Date',['class'=>'col-sm-3 control-label']) }}
						<div class='col-sm-9'>
							<div class="input-group date">
								{{ Form::text('inv_expiry_date',null, ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'inv_expiry_date']) }}
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('inv_expiry_date')) <p class="help-block">{{ $errors->first('inv_expiry_date') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/inventories" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<script>
		$('#inv_expiry_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	</script>
