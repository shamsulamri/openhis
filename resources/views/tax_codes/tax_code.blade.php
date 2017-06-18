
    <div class='form-group  @if ($errors->has('tax_name')) has-error @endif'>
        <label for='tax_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('tax_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('tax_name')) <p class="help-block">{{ $errors->first('tax_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tax_rate')) has-error @endif'>
        <label for='tax_rate' class='col-sm-3 control-label'>Rate<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('tax_rate', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('tax_rate')) <p class="help-block">{{ $errors->first('tax_rate') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/tax_codes" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
