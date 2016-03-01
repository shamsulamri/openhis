
    <div class='form-group  @if ($errors->has('order_id')) has-error @endif'>
        <label for='order_id' class='col-sm-2 control-label'>order_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('order_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('order_id')) <p class="help-block">{{ $errors->first('order_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('investigation_start')) has-error @endif'>
        <label for='investigation_start' class='col-sm-2 control-label'>investigation_start<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('investigation_start', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('investigation_start')) <p class="help-block">{{ $errors->first('investigation_start') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('urgency_code')) has-error @endif'>
        {{ Form::label('urgency_code', 'urgency_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('urgency_code', $urgency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('urgency_code')) <p class="help-block">{{ $errors->first('urgency_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('investigation_recur')) has-error @endif'>
        {{ Form::label('investigation_recur', 'investigation_recur',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('investigation_recur', '1') }}
            @if ($errors->has('investigation_recur')) <p class="help-block">{{ $errors->first('investigation_recur') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('investigation_period')) has-error @endif'>
        {{ Form::label('investigation_period', 'investigation_period',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('investigation_period', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('investigation_period')) <p class="help-block">{{ $errors->first('investigation_period') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
        {{ Form::label('period_code', 'period_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_code')) has-error @endif'>
        {{ Form::label('frequency_code', 'frequency_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('frequency_code', $frequency,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/order_investigations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
