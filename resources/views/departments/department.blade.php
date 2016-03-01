
    <div class='form-group  @if ($errors->has('department_name')) has-error @endif'>
        <label for='department_name' class='col-sm-2 control-label'>department_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('department_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'200']) }}
            @if ($errors->has('department_name')) <p class="help-block">{{ $errors->first('department_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/departments" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
