
    <div class='form-group  @if ($errors->has('period_name')) has-error @endif'>
        <label for='period_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('period_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('period_name')) <p class="help-block">{{ $errors->first('period_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_label')) has-error @endif'>
        {{ Form::label('period_label', 'Label',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('period_label', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('period_label')) <p class="help-block">{{ $errors->first('period_label') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_value')) has-error @endif'>
        {{ Form::label('period_value', 'Value',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('period_value', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('period_value')) <p class="help-block">{{ $errors->first('period_value') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/periods" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
