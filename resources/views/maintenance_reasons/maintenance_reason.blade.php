
    <div class='form-group  @if ($errors->has('reason_name')) has-error @endif'>
        <label for='reason_name' class='col-sm-2 control-label'>reason_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('reason_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('reason_name')) <p class="help-block">{{ $errors->first('reason_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/maintenance_reasons" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
