

    <div class='form-group  @if ($errors->has('payment_amount')) has-error @endif'>
        <label for='payment_amount' class='col-sm-2 control-label'>Amount<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('payment_amount', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('payment_amount')) <p class="help-block">{{ $errors->first('payment_amount') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('payment_code')) has-error @endif'>
        <label for='payment_code' class='col-sm-2 control-label'>Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('payment_code', $payment_methods,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('payment_code')) <p class="help-block">{{ $errors->first('payment_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('payment_description')) has-error @endif'>
        {{ Form::label('payment_description', 'Note',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('payment_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('payment_description')) <p class="help-block">{{ $errors->first('payment_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bills/{{ $payment->encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

    {{ Form::hidden('encounter_id', null) }}
