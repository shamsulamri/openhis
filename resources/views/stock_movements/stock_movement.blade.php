
    <div class='form-group  @if ($errors->has('move_name')) has-error @endif'>
        <label for='move_name' class='col-sm-3 control-label'>move_name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('move_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('move_name')) <p class="help-block">{{ $errors->first('move_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/stock_movements" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
