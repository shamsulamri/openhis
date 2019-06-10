
    <div class='form-group  @if ($errors->has('bill_total')) has-error @endif'>
        <label for='bill_total' class='col-sm-2 control-label'>bill_total<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('bill_total', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_total')) <p class="help-block">{{ $errors->first('bill_total') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_grand_total')) has-error @endif'>
        <label for='bill_grand_total' class='col-sm-2 control-label'>bill_grand_total<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('bill_grand_total', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_grand_total')) <p class="help-block">{{ $errors->first('bill_grand_total') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bill_total_payable')) has-error @endif'>
        <label for='bill_total_payable' class='col-sm-2 control-label'>bill_total_payable<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('bill_total_payable', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('bill_total_payable')) <p class="help-block">{{ $errors->first('bill_total_payable') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('deleted_at')) has-error @endif'>
        {{ Form::label('deleted_at', 'deleted_at',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('deleted_at', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('deleted_at')) <p class="help-block">{{ $errors->first('deleted_at') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bill_totals" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
