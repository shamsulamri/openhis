
    <div class='form-group  @if ($errors->has('marital_name')) has-error @endif'>
        <label for='marital_name' class='col-sm-2 control-label'>marital_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('marital_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('marital_name')) <p class="help-block">{{ $errors->first('marital_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/marital_statuses" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
