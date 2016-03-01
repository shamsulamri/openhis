
    <div class='form-group  @if ($errors->has('urgency_name')) has-error @endif'>
        <label for='urgency_name' class='col-sm-2 control-label'>urgency_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('urgency_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('urgency_name')) <p class="help-block">{{ $errors->first('urgency_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/urgencies" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
