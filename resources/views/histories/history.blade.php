
    <div class='form-group  @if ($errors->has('history_name')) has-error @endif'>
        <label for='history_name' class='col-sm-2 control-label'>history_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('history_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('history_name')) <p class="help-block">{{ $errors->first('history_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/histories" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
