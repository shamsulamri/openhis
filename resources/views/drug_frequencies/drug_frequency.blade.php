
    <div class='form-group  @if ($errors->has('frequency_name')) has-error @endif'>
        <label for='frequency_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('frequency_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('frequency_name')) <p class="help-block">{{ $errors->first('frequency_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_label')) has-error @endif'>
        {{ Form::label('frequency_label', 'Label',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('frequency_label', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('frequency_label')) <p class="help-block">{{ $errors->first('frequency_label') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_mar')) has-error @endif'>
        {{ Form::label('frequency_mar', 'MAR Label',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('frequency_mar', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('frequency_mar')) <p class="help-block">{{ $errors->first('frequency_mar') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('frequency_value')) has-error @endif'>
        {{ Form::label('frequency_value', 'Frequency',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('frequency_value', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('frequency_value')) <p class="help-block">{{ $errors->first('frequency_value') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('frequency_index')) has-error @endif'>
        {{ Form::label('frequency_index', 'Index',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('frequency_index', null, ['class'=>'form-control','placeholder'=>'Item position in list','maxlength'=>'50']) }}
            @if ($errors->has('frequency_index')) <p class="help-block">{{ $errors->first('frequency_index') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/drug_frequencies" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
