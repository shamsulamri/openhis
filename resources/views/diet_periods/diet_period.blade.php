
    <div class='form-group  @if ($errors->has('period_name')) has-error @endif'>
        <label for='period_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('period_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('period_name')) <p class="help-block">{{ $errors->first('period_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_position')) has-error @endif'>
        {{ Form::label('period_position', 'Position',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('period_position', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('period_position')) <p class="help-block">{{ $errors->first('period_position') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/diet_periods" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
