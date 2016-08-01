
    <div class='form-group  @if ($errors->has('encounter_id')) has-error @endif'>
        <label for='encounter_id' class='col-sm-3 control-label'>encounter_id<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('encounter_id')) <p class="help-block">{{ $errors->first('encounter_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_grand_total')) has-error @endif'>
        <label for='bill_grand_total' class='col-sm-3 control-label'>bill_grand_total<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_grand_total', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_grand_total')) <p class="help-block">{{ $errors->first('bill_grand_total') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_payment_total')) has-error @endif'>
        <label for='bill_payment_total' class='col-sm-3 control-label'>bill_payment_total<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_payment_total', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_payment_total')) <p class="help-block">{{ $errors->first('bill_payment_total') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_deposit_total')) has-error @endif'>
        <label for='bill_deposit_total' class='col-sm-3 control-label'>bill_deposit_total<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_deposit_total', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_deposit_total')) <p class="help-block">{{ $errors->first('bill_deposit_total') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_outstanding')) has-error @endif'>
        <label for='bill_outstanding' class='col-sm-3 control-label'>bill_outstanding<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_outstanding', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_outstanding')) <p class="help-block">{{ $errors->first('bill_outstanding') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_change')) has-error @endif'>
        <label for='bill_change' class='col-sm-3 control-label'>bill_change<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bill_change', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_change')) <p class="help-block">{{ $errors->first('bill_change') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/bills" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
