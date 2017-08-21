
    <div class='form-group  @if ($errors->has('stock_id')) has-error @endif'>
        <label for='stock_id' class='col-sm-2 control-label'>stock_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('stock_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('stock_id')) <p class="help-block">{{ $errors->first('stock_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_number')) has-error @endif'>
        <label for='batch_number' class='col-sm-2 control-label'>batch_number<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('batch_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('batch_number')) <p class="help-block">{{ $errors->first('batch_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('batch_quantity')) has-error @endif'>
        <label for='batch_quantity' class='col-sm-2 control-label'>batch_quantity<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('batch_quantity', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('batch_quantity')) <p class="help-block">{{ $errors->first('batch_quantity') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_batches" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
