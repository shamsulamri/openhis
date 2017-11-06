
    <div class='form-group  @if ($errors->has('order_id')) has-error @endif'>
        <label for='order_id' class='col-sm-2 control-label'>order_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_id')) <p class="help-block">{{ $errors->first('order_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('medication_slot')) has-error @endif'>
        <label for='medication_slot' class='col-sm-2 control-label'>medication_slot<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('medication_slot', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('medication_slot')) <p class="help-block">{{ $errors->first('medication_slot') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('medication_datetime')) has-error @endif'>
        <label for='medication_datetime' class='col-sm-2 control-label'>medication_datetime<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('medication_datetime', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('medication_datetime')) <p class="help-block">{{ $errors->first('medication_datetime') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/medication_records" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
