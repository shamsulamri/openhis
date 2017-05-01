
    <div class='form-group  @if ($errors->has('frequency_name')) has-error @endif'>
        <label for='frequency_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('frequency_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('frequency_name')) <p class="help-block">{{ $errors->first('frequency_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('frequency_mins')) has-error @endif'>
        <label for='frequency_mins' class='col-sm-3 control-label'>Minutes<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('frequency_mins', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('frequency_mins')) <p class="help-block">{{ $errors->first('frequency_mins') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/frequencies" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
